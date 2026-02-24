<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Core\View;
use App\Models\Setting;
use App\Models\Subscriber;

class SubscriberController
{
    /**
     * Gate: require authentication.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/subscribers
     * ----------------------------------------------------------------*/
    public function index(Request $request, array $params): void
    {
        $status = $request->get('status', '');
        $search = $request->get('search', '');
        $page   = max(1, (int) $request->get('page', 1));
        $perPage = 20;

        $total     = Subscriber::count($status, $search);
        $paginator = new Paginator($total, $perPage, $page);
        $subscribers = Subscriber::all($paginator->perPage, $paginator->offset, $status, $search);

        $activeCount = Subscriber::activeCount();
        $totalCount  = Subscriber::count();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/subscribers/index', [
            'pageTitle'    => 'Subscribers',
            'currentPage'  => 'subscribers',
            'subscribers'  => $subscribers,
            'paginator'    => $paginator,
            'filters'      => ['status' => $status, 'search' => $search],
            'activeCount'  => $activeCount,
            'totalCount'   => $totalCount,
        ]);
    }

    /* ------------------------------------------------------------------
     *  EDIT  --  GET /admin/subscribers/{id}/edit
     * ----------------------------------------------------------------*/
    public function edit(Request $request, array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $subscriber = Subscriber::find($id);

        if (!$subscriber) {
            Session::flash('error', 'Subscriber not found.');
            Response::redirect(url('admin/subscribers'));
            return;
        }

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/subscribers/edit', [
            'pageTitle'   => 'Edit Subscriber',
            'currentPage' => 'subscribers',
            'subscriber'  => $subscriber,
        ]);
    }

    /* ------------------------------------------------------------------
     *  UPDATE  --  POST /admin/subscribers/{id}/update
     * ----------------------------------------------------------------*/
    public function update(Request $request, array $params): void
    {
        Csrf::check();

        $id = (int) ($params['id'] ?? 0);
        $subscriber = Subscriber::find($id);

        if (!$subscriber) {
            Session::flash('error', 'Subscriber not found.');
            Response::redirect(url('admin/subscribers'));
            return;
        }

        $data = [
            'email'  => trim((string) $request->post('email', '')),
            'name'   => trim((string) $request->post('name', '')),
            'status' => trim((string) $request->post('status', 'active')),
        ];

        $validator = new Validator($data);
        $valid = $validator->validate([
            'email'  => ['required', 'email', 'unique:subscribers,email,' . $id],
            'status' => ['required', 'in:active,unsubscribed'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url('admin/subscribers/' . $id . '/edit'));
            return;
        }

        // If status changed to unsubscribed, set unsubscribed_at
        if ($data['status'] === 'unsubscribed' && $subscriber['status'] === 'active') {
            $data['unsubscribed_at'] = date('Y-m-d H:i:s');
        } elseif ($data['status'] === 'active' && $subscriber['status'] === 'unsubscribed') {
            $data['unsubscribed_at'] = null;
            $data['subscribed_at'] = date('Y-m-d H:i:s');
        }

        Subscriber::update($id, $data);

        Session::flash('success', 'Subscriber updated successfully.');
        Response::redirect(url('admin/subscribers'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/subscribers/{id}/delete
     * ----------------------------------------------------------------*/
    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $id = (int) ($params['id'] ?? 0);
        Subscriber::delete($id);

        Session::flash('success', 'Subscriber deleted.');
        Response::redirect(url('admin/subscribers'));
    }

    /* ------------------------------------------------------------------
     *  COMPOSE  --  GET /admin/subscribers/compose
     * ----------------------------------------------------------------*/
    public function compose(Request $request, array $params): void
    {
        $activeCount = Subscriber::activeCount();
        $emailLogs   = Subscriber::emailLogs(10);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/subscribers/compose', [
            'pageTitle'    => 'Send Email',
            'currentPage'  => 'subscribers',
            'activeCount'  => $activeCount,
            'emailLogs'    => $emailLogs,
        ]);
    }

    /* ------------------------------------------------------------------
     *  SEND  --  POST /admin/subscribers/send
     * ----------------------------------------------------------------*/
    public function send(Request $request, array $params): void
    {
        Csrf::check();

        $subject = trim((string) $request->post('subject', ''));
        $body    = trim((string) $request->post('body', ''));

        $validator = new Validator(['subject' => $subject, 'body' => $body]);
        $valid = $validator->validate([
            'subject' => ['required', 'max:500'],
            'body'    => ['required'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', ['subject' => $subject, 'body' => $body]);
            Response::redirect(url('admin/subscribers/compose'));
            return;
        }

        // Get all active subscribers
        $subscribers = Subscriber::activeEmails();
        $sentCount = 0;
        $errors = [];

        // Load SMTP config from settings
        $smtpHost = Setting::get('smtp_host', '');
        $smtpPort = (int) Setting::get('smtp_port', '587');
        $smtpUser = Setting::get('smtp_user', '');
        $smtpPass = Setting::get('smtp_pass', '');
        $smtpEncryption = Setting::get('smtp_encryption', 'tls');
        $fromEmail = Setting::get('smtp_from_email', 'noreply@example.com');
        $fromName  = Setting::get('smtp_from_name', 'NewsCMS');

        // Build unsubscribe base URL
        $unsubBase = url('newsletter/unsubscribe');

        foreach ($subscribers as $sub) {
            $personalBody = $body;
            // Add unsubscribe link to each email
            $unsubLink = $unsubBase . '?email=' . urlencode($sub['email']);
            $personalBody .= "\n\n---\nTo unsubscribe, click here: " . $unsubLink;

            $sent = self::sendEmail(
                $sub['email'],
                $subject,
                $personalBody,
                $fromEmail,
                $fromName,
                $smtpHost,
                $smtpPort,
                $smtpUser,
                $smtpPass,
                $smtpEncryption
            );

            if ($sent) {
                $sentCount++;
            } else {
                $errors[] = $sub['email'];
            }
        }

        // Log the email
        $userId = (int) Session::get('user_id', 0);
        Subscriber::logEmail($subject, $body, $sentCount, $userId);

        if ($sentCount > 0) {
            $msg = "Email sent to {$sentCount} subscriber(s).";
            if (!empty($errors)) {
                $msg .= " Failed for " . count($errors) . " address(es).";
            }
            Session::flash('success', $msg);
        } else {
            Session::flash('error', 'Failed to send emails. Please check your SMTP configuration in Settings.');
        }

        Response::redirect(url('admin/subscribers/compose'));
    }

    /* ------------------------------------------------------------------
     *  EMAIL CONFIG  --  GET /admin/subscribers/email-config
     * ----------------------------------------------------------------*/
    public function emailConfig(Request $request, array $params): void
    {
        $settings = [
            'smtp_host'       => Setting::get('smtp_host', ''),
            'smtp_port'       => Setting::get('smtp_port', '587'),
            'smtp_user'       => Setting::get('smtp_user', ''),
            'smtp_pass'       => Setting::get('smtp_pass', ''),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'smtp_from_email' => Setting::get('smtp_from_email', 'noreply@example.com'),
            'smtp_from_name'  => Setting::get('smtp_from_name', 'NewsCMS'),
        ];

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/subscribers/email-config', [
            'pageTitle'    => 'Email Configuration',
            'currentPage'  => 'subscribers',
            'settings'     => $settings,
        ]);
    }

    /* ------------------------------------------------------------------
     *  SAVE EMAIL CONFIG  --  POST /admin/subscribers/email-config/save
     * ----------------------------------------------------------------*/
    public function saveEmailConfig(Request $request, array $params): void
    {
        Csrf::check();

        $keys = ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_email', 'smtp_from_name'];

        foreach ($keys as $key) {
            $value = trim((string) $request->post($key, ''));
            Setting::set($key, $value);
        }

        Session::flash('success', 'Email configuration saved successfully.');
        Response::redirect(url('admin/subscribers/email-config'));
    }

    /* ------------------------------------------------------------------
     *  SEND EMAIL HELPER
     * ----------------------------------------------------------------*/
    private static function sendEmail(
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
        // If SMTP is configured, use SMTP; otherwise use PHP mail()
        if (!empty($smtpHost) && !empty($smtpUser)) {
            return self::sendViaSMTP($to, $subject, $body, $fromEmail, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass, $encryption);
        }

        // Fallback to PHP mail()
        $headers = "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $htmlBody = nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));

        return @mail($to, $subject, $htmlBody, $headers);
    }

    /**
     * Send email via SMTP using fsockopen.
     */
    private static function sendViaSMTP(
        string $to,
        string $subject,
        string $body,
        string $fromEmail,
        string $fromName,
        string $smtpHost,
        int $smtpPort,
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

            $response = fgets($socket, 512);

            // EHLO
            fwrite($socket, "EHLO localhost\r\n");
            $response = '';
            while ($line = fgets($socket, 512)) {
                $response .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }

            // STARTTLS for TLS
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

            // AUTH LOGIN
            fwrite($socket, "AUTH LOGIN\r\n");
            fgets($socket, 512);
            fwrite($socket, base64_encode($smtpUser) . "\r\n");
            fgets($socket, 512);
            fwrite($socket, base64_encode($smtpPass) . "\r\n");
            $authResponse = fgets($socket, 512);

            if (substr($authResponse, 0, 3) !== '235') {
                fwrite($socket, "QUIT\r\n");
                fclose($socket);
                error_log("SMTP AUTH failed: {$authResponse}");
                return false;
            }

            // MAIL FROM
            fwrite($socket, "MAIL FROM:<{$fromEmail}>\r\n");
            fgets($socket, 512);

            // RCPT TO
            fwrite($socket, "RCPT TO:<{$to}>\r\n");
            fgets($socket, 512);

            // DATA
            fwrite($socket, "DATA\r\n");
            fgets($socket, 512);

            $htmlBody = nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));

            $message  = "From: {$fromName} <{$fromEmail}>\r\n";
            $message .= "To: {$to}\r\n";
            $message .= "Subject: {$subject}\r\n";
            $message .= "MIME-Version: 1.0\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "\r\n";
            $message .= $htmlBody;
            $message .= "\r\n.\r\n";

            fwrite($socket, $message);
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
