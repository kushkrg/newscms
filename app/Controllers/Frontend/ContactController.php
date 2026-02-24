<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Csrf;
use App\Core\Recaptcha;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\SEO;
use App\Core\Validator;
use App\Core\View;
use App\Models\Message;
use App\Models\Page;

class ContactController
{
    /**
     * Display the contact page with the form.
     * GET /contact
     */
    public function show(Request $request, array $params): void
    {
        // Try to load the 'contact' page from the DB for header/meta content
        $page = Page::findBySlug('contact');

        $seo = new SEO();
        $seo->setTitle($page['title'] ?? 'Contact Us')
            ->setDescription($page['meta_description'] ?? 'Get in touch with us. We\'d love to hear from you.')
            ->setCanonical(url('contact'));

        $seo->setBreadcrumbs([
            ['name' => 'Home',       'url' => url('/')],
            ['name' => 'Contact Us', 'url' => url('contact')],
        ]);

        $view = new View();
        $view->setLayout('layouts/main');
        echo $view->render('frontend/contact', [
            'pageTitle' => $page['title'] ?? 'Contact Us',
            'page'      => $page,
            'seo'       => $seo,
            'old'       => Session::getFlash('old') ?? [],
        ]);
    }

    /**
     * Handle contact form submission.
     * POST /contact
     */
    public function submit(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'name'    => trim((string) $request->post('name', '')),
            'email'   => trim((string) $request->post('email', '')),
            'subject' => trim((string) $request->post('subject', '')),
            'message' => trim((string) $request->post('message', '')),
        ];

        // reCAPTCHA v3 verification
        $recaptchaToken = (string) $request->post('g-recaptcha-response', '');
        if (!Recaptcha::verify($recaptchaToken, 'contact')) {
            Session::flash('error', 'reCAPTCHA verification failed. Please try again.');
            Session::flash('old', $data);
            Response::redirect(url('contact'));
            return;
        }

        // Validate
        $validator = new Validator($data);
        $valid = $validator->validate([
            'name'    => ['required', 'max:100'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'max:200'],
            'message' => ['required', 'max:5000'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url('contact'));
            return;
        }

        // Send email (if SMTP is configured) or store the message
        $this->sendContactEmail($data);

        // Store in DB
        Message::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'subject'    => $data['subject'],
            'message'    => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        Session::flash('contact_success', true);
        Response::redirect(url('contact'));
    }

    /**
     * Attempt to send email using configured SMTP, or log it.
     */
    private function sendContactEmail(array $data): void
    {
        $adminEmail = \App\Models\Setting::get('smtp_from_email', '');

        if (!$adminEmail) {
            // No SMTP configured — just log it
            $logDir  = dirname(__DIR__, 2) . '/storage/logs';
            $logFile = $logDir . '/contact_messages.log';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            $entry = date('Y-m-d H:i:s') . " | "
                   . "Name: {$data['name']} | "
                   . "Email: {$data['email']} | "
                   . "Subject: {$data['subject']} | "
                   . "Message: " . substr($data['message'], 0, 500) . "\n";
            file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
            return;
        }

        // Use PHP's built-in mail() or SMTP
        $headers  = "From: {$data['name']} <{$data['email']}>\r\n";
        $headers .= "Reply-To: {$data['email']}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($adminEmail, "[Contact] {$data['subject']}", $data['message'], $headers);
    }
}
