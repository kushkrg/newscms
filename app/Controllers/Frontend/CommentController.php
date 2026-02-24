<?php

declare(strict_types=1);

namespace App\Controllers\Frontend;

use App\Core\Csrf;
use App\Core\Recaptcha;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Comment;
use App\Models\Post;

class CommentController
{
    /**
     * Store a new comment submitted from a post page.
     *
     * Performs CSRF validation, honeypot check, rate limiting, input validation,
     * HTML stripping, and creates the comment with a "pending" status.
     */
    public function store(Request $request, array $params): void
    {
        // Must be POST
        if (!$request->isPost()) {
            Response::notFound();
            return;
        }

        // CSRF protection
        Csrf::check();

        // reCAPTCHA v3 verification
        $recaptchaToken = (string) $request->post('g-recaptcha-response', '');
        if (!Recaptcha::verify($recaptchaToken, 'comment')) {
            $postId = (int) $request->post('post_id', 0);
            $post   = $postId ? Post::find($postId) : null;
            $redirectUrl = $post ? url('article/' . $post['slug'] . '#comments') : url('/');
            Session::flash('error', 'reCAPTCHA verification failed. Please try again.');
            Response::redirect($redirectUrl);
            return;
        }

        // Honeypot: hidden field "website_url" should be empty
        $honeypot = $request->post('website_url', '');
        if ($honeypot !== '') {
            // Bot detected -- silently redirect back
            $postId = (int) $request->post('post_id', 0);
            $post   = $postId ? Post::find($postId) : null;
            $redirectUrl = $post ? url('article/' . $post['slug']) : url('/');
            Response::redirect($redirectUrl);
            return;
        }

        // Rate limiting: block if last comment was less than 10 seconds ago
        $lastCommentTime = (int) Session::get('last_comment_time', 0);
        if ($lastCommentTime && (time() - $lastCommentTime) < 10) {
            Session::flash('error', 'You are posting too quickly. Please wait a moment.');
            $postId = (int) $request->post('post_id', 0);
            $post   = $postId ? Post::find($postId) : null;
            $redirectUrl = $post ? url('article/' . $post['slug'] . '#comments') : url('/');
            Response::redirect($redirectUrl);
            return;
        }

        // Validate input
        $data = [
            'author_name'  => trim((string) $request->post('author_name', '')),
            'author_email' => trim((string) $request->post('author_email', '')),
            'content'      => trim((string) $request->post('content', '')),
            'post_id'      => $request->post('post_id', ''),
            'parent_id'    => $request->post('parent_id', null),
        ];

        $validator = new Validator($data);
        $valid = $validator->validate([
            'author_name'  => ['required', 'max:100'],
            'author_email' => ['required', 'email', 'max:255'],
            'content'      => ['required', 'max:2000'],
            'post_id'      => ['required', 'integer'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            $postId = (int) $data['post_id'];
            $post   = $postId ? Post::find($postId) : null;
            $redirectUrl = $post ? url('article/' . $post['slug'] . '#comments') : url('/');
            Response::redirect($redirectUrl);
            return;
        }

        // Verify post exists and is published (i.e. allows comments)
        $post = Post::find((int) $data['post_id']);
        if (!$post || $post['status'] !== 'published') {
            Session::flash('error', 'The post you are trying to comment on does not exist.');
            Response::redirect(url('/'));
            return;
        }

        // Optionally check if comments are disabled on this post
        if (isset($post['allow_comments']) && !$post['allow_comments']) {
            Session::flash('error', 'Comments are closed for this article.');
            Response::redirect(url('article/' . $post['slug']));
            return;
        }

        // Strip ALL HTML from the comment content
        $cleanContent = strip_tags($data['content']);

        // Build the comment record
        $commentData = [
            'post_id'      => (int) $data['post_id'],
            'parent_id'    => !empty($data['parent_id']) ? (int) $data['parent_id'] : null,
            'author_name'  => $data['author_name'],
            'author_email' => $data['author_email'],
            'content'      => $cleanContent,
            'status'       => 'pending',
            'author_ip'    => $request->ip(),
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        Comment::create($commentData);

        // Update rate-limit timestamp
        Session::set('last_comment_time', time());

        // Flash success and redirect back to the post
        Session::flash('success', 'Comment submitted for moderation.');
        Response::redirect(url('article/' . $post['slug'] . '#comments'));
    }
}
