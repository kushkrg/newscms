<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\Message;
use App\Models\Setting;

class MessageController
{
    public function middleware(): void
    {
        Auth::requireRole(['super_admin', 'admin', 'editor']);
    }

    /**
     * List messages with optional status filter and pagination.
     */
    public function index(Request $request, array $params): void
    {
        $status  = $request->get('status', '');
        $status  = in_array($status, ['unread', 'read', 'replied'], true) ? $status : null;
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = 20;

        $total     = Message::countByStatus($status);
        $messages  = Message::paginate($status, $perPage, ($page - 1) * $perPage);
        $paginator = new Paginator($total, $perPage, $page);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/messages/index', [
            'pageTitle'   => 'Messages',
            'currentPage' => 'messages',
            'messages'    => $messages,
            'status'      => $status,
            'paginator'   => $paginator,
            'counts'      => [
                'all'     => Message::countByStatus(),
                'unread'  => Message::countByStatus('unread'),
                'read'    => Message::countByStatus('read'),
                'replied' => Message::countByStatus('replied'),
            ],
        ]);
    }

    /**
     * Show a single message with reply form.
     */
    public function show(Request $request, array $params): void
    {
        $id      = (int) ($params['id'] ?? 0);
        $message = Message::find($id);

        if (!$message) {
            Session::flash('error', 'Message not found.');
            Response::redirect(url('admin/messages'));
            return;
        }

        // Mark as read if currently unread
        if ($message['status'] === 'unread') {
            Message::updateStatus($id, 'read');
            $message['status'] = 'read';
        }

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/messages/show', [
            'pageTitle'   => 'Message from ' . ($message['name'] ?? 'Unknown'),
            'currentPage' => 'messages',
            'message'     => $message,
        ]);
    }

    /**
     * Reply to a message (sends email and stores the reply).
     */
    public function reply(Request $request, array $params): void
    {
        Csrf::check();

        $id      = (int) ($params['id'] ?? 0);
        $message = Message::find($id);

        if (!$message) {
            Session::flash('error', 'Message not found.');
            Response::redirect(url('admin/messages'));
            return;
        }

        $replyText = trim((string) $request->post('reply_text', ''));

        $validator = new Validator(['reply_text' => $replyText]);
        $valid = $validator->validate([
            'reply_text' => ['required', 'max:10000'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url('admin/messages/' . $id));
            return;
        }

        // Send the reply email
        $sent = $this->sendReplyEmail($message, $replyText);

        // Save reply to DB
        $userId = (int) Session::get('user_id', 0);
        Message::saveReply($id, $replyText, $userId);

        if ($sent) {
            Session::flash('success', 'Reply sent successfully to ' . $message['email']);
        } else {
            Session::flash('success', 'Reply saved. Email delivery may have failed — check your SMTP settings.');
        }

        Response::redirect(url('admin/messages/' . $id));
    }

    /**
     * Delete a message.
     */
    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $id = (int) ($params['id'] ?? 0);
        Message::delete($id);

        Session::flash('success', 'Message deleted.');
        Response::redirect(url('admin/messages'));
    }

    /**
     * Send the reply email using configured SMTP or PHP mail().
     */
    private function sendReplyEmail(array $message, string $replyText): bool
    {
        $fromEmail  = Setting::get('smtp_from_email', '');
        $fromName   = Setting::get('smtp_from_name', Setting::get('site_name', 'Blog'));
        $smtpHost   = Setting::get('smtp_host', '');
        $smtpPort   = (int) Setting::get('smtp_port', 587);
        $smtpUser   = Setting::get('smtp_user', '');
        $smtpPass   = Setting::get('smtp_pass', '');
        $encryption = Setting::get('smtp_encryption', 'tls');

        $to      = $message['email'];
        $subject = 'Re: ' . $message['subject'];

        // Build a simple HTML email body
        $htmlBody = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
        $htmlBody .= '<p>Hi ' . htmlspecialchars($message['name'], ENT_QUOTES, 'UTF-8') . ',</p>';
        $htmlBody .= '<div style="white-space: pre-wrap; line-height: 1.6;">' . htmlspecialchars($replyText, ENT_QUOTES, 'UTF-8') . '</div>';
        $htmlBody .= '<hr style="border: none; border-top: 1px solid #eee; margin: 24px 0;">';
        $htmlBody .= '<p style="color: #888; font-size: 12px;">In reply to your message:<br>';
        $htmlBody .= '<em>' . htmlspecialchars(mb_strimwidth($message['message'], 0, 500, '...'), ENT_QUOTES, 'UTF-8') . '</em></p>';
        $htmlBody .= '</div>';

        // Try SMTP first, then fallback to mail()
        if (!empty($smtpHost) && !empty($smtpUser)) {
            return $this->sendViaSMTP($to, $subject, $htmlBody, $fromEmail, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass, $encryption);
        }

        if (empty($fromEmail)) {
            $fromEmail = 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        }

        $headers  = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return @mail($to, $subject, $htmlBody, $headers);
    }

    /**
     * Send email via SMTP using fsockopen.
     */
    private function sendViaSMTP(
        string $to,
        string $subject,
        string $body,
        string $fromEmail,
        string $fromName,
        string $smtpHost,
        int    $smtpPort,
        string $smtpUser,
        string $smtpPass,
        string $encryption
    ): bool {
        try {
            $host = $encryption === 'ssl' ? "ssl://{$smtpHost}" : $smtpHost;
            $socket = @fsockopen($host, $smtpPort, $errno, $errstr, 10);

            if (!$socket) {
                error_log("SMTP connection failed: {$errstr} ({$errno})");
                return false;
            }

            fgets($socket, 512);

            fwrite($socket, "EHLO localhost\r\n");
            $response = '';
            while ($line = fgets($socket, 512)) {
                $response .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }

            if ($encryption === 'tls') {
                fwrite($socket, "STARTTLS\r\n");
                fgets($socket, 512);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fwrite($socket, "EHLO localhost\r\n");
                $response = '';
                while ($line = fgets($socket, 512)) {
                    $response .= $line;
                    if (substr($line, 3, 1) === ' ') break;
                }
            }

            fwrite($socket, "AUTH LOGIN\r\n");
            fgets($socket, 512);
            fwrite($socket, base64_encode($smtpUser) . "\r\n");
            fgets($socket, 512);
            fwrite($socket, base64_encode($smtpPass) . "\r\n");
            $authResponse = fgets($socket, 512);

            if (!str_starts_with(trim($authResponse), '235')) {
                fwrite($socket, "QUIT\r\n");
                fclose($socket);
                error_log("SMTP auth failed: {$authResponse}");
                return false;
            }

            fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
            fgets($socket, 512);
            fwrite($socket, "RCPT TO:<{$to}>\r\n");
            fgets($socket, 512);
            fwrite($socket, "DATA\r\n");
            fgets($socket, 512);

            $msg  = "From: {$fromName} <{$fromEmail}>\r\n";
            $msg .= "To: {$to}\r\n";
            $msg .= "Subject: {$subject}\r\n";
            $msg .= "MIME-Version: 1.0\r\n";
            $msg .= "Content-Type: text/html; charset=UTF-8\r\n";
            $msg .= "\r\n";
            $msg .= $body . "\r\n";
            $msg .= ".\r\n";

            fwrite($socket, $msg);
            fgets($socket, 512);
            fwrite($socket, "QUIT\r\n");
            fclose($socket);

            return true;
        } catch (\Throwable $e) {
            error_log("SMTP error: " . $e->getMessage());
            return false;
        }
    }
}
