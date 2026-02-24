<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Csrf;
use App\Core\Recaptcha;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Subscriber;

class SubscribeController
{
    /**
     * Handle newsletter subscription form.
     * POST /newsletter/subscribe
     */
    public function store(Request $request, array $params): void
    {
        Csrf::check();

        // reCAPTCHA v3 verification
        $recaptchaToken = (string) $request->post('g-recaptcha-response', '');
        if (!Recaptcha::verify($recaptchaToken, 'subscribe')) {
            Session::flash('newsletter_error', 'reCAPTCHA verification failed. Please try again.');
            $this->redirectBack();
            return;
        }

        $email = trim((string) $request->post('email', ''));
        $name  = trim((string) $request->post('name', ''));

        $validator = new Validator(['email' => $email]);
        $valid = $validator->validate([
            'email' => ['required', 'email'],
        ]);

        if (!$valid) {
            Session::flash('newsletter_error', $validator->firstError());
            $this->redirectBack();
            return;
        }

        // Check if already subscribed
        $existing = Subscriber::findByEmail($email);
        if ($existing && $existing['status'] === 'active') {
            Session::flash('newsletter_success', 'You are already subscribed! Thank you.');
            $this->redirectBack();
            return;
        }

        // Subscribe (handles re-subscribe for unsubscribed users too)
        Subscriber::subscribe($email, $name);

        Session::flash('newsletter_success', 'Thank you for subscribing! You\'ll receive our latest updates.');
        $this->redirectBack();
    }

    /**
     * Handle unsubscribe link.
     * GET /newsletter/unsubscribe?email=...
     */
    public function unsubscribe(Request $request, array $params): void
    {
        $email = trim((string) $request->get('email', ''));

        if ($email && Subscriber::unsubscribe($email)) {
            Session::flash('success', 'You have been successfully unsubscribed.');
        } else {
            Session::flash('error', 'Email not found or already unsubscribed.');
        }

        Response::redirect(url('/'));
    }

    /**
     * Redirect back to the referring page.
     */
    private function redirectBack(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        Response::redirect($referer);
    }
}
