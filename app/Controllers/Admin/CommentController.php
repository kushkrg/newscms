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
use App\Core\View;
use App\Models\Comment;

class CommentController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/comments
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $status  = $request->get('status', '');
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = 20;
        $total   = Comment::adminCount($status);

        $paginator = new Paginator($total, $perPage, $page);
        $comments  = Comment::adminList($status, $paginator->perPage, $paginator->offset);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/comments/index', [
            'pageTitle'   => 'Comments',
            'currentPage' => 'comments',
            'comments'  => $comments,
            'status'    => $status,
            'paginator' => $paginator,
        ]);
    }

    /* ------------------------------------------------------------------
     *  APPROVE  --  POST /admin/comments/{id}/approve
     * ----------------------------------------------------------------*/

    public function approve(Request $request, array $params): void
    {
        Csrf::check();

        $commentId = (int) $params['id'];
        $comment   = Comment::find($commentId);
        if (!$comment) {
            Session::flash('error', 'Comment not found.');
            Response::redirect(url('admin/comments'));
        }

        Comment::updateStatus($commentId, 'approved');

        // Update the post's comment_count
        if (!empty($comment['post_id'])) {
            $this->updatePostCommentCount((int) $comment['post_id']);
        }

        Session::flash('success', 'Comment approved.');
        Response::redirect(url('admin/comments'));
    }

    /* ------------------------------------------------------------------
     *  SPAM  --  POST /admin/comments/{id}/spam
     * ----------------------------------------------------------------*/

    public function spam(Request $request, array $params): void
    {
        Csrf::check();

        $commentId = (int) $params['id'];
        $comment   = Comment::find($commentId);
        if (!$comment) {
            Session::flash('error', 'Comment not found.');
            Response::redirect(url('admin/comments'));
        }

        Comment::updateStatus($commentId, 'spam');

        // If the comment was previously approved, recount
        if ($comment['status'] === 'approved' && !empty($comment['post_id'])) {
            $this->updatePostCommentCount((int) $comment['post_id']);
        }

        Session::flash('success', 'Comment marked as spam.');
        Response::redirect(url('admin/comments'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/comments/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $commentId = (int) $params['id'];
        $comment   = Comment::find($commentId);
        if (!$comment) {
            Session::flash('error', 'Comment not found.');
            Response::redirect(url('admin/comments'));
        }

        $postId        = $comment['post_id'] ?? null;
        $wasApproved   = $comment['status'] === 'approved';

        Comment::delete($commentId);

        // Recount if the deleted comment was approved
        if ($wasApproved && $postId) {
            $this->updatePostCommentCount((int) $postId);
        }

        Session::flash('success', 'Comment deleted.');
        Response::redirect(url('admin/comments'));
    }

    /* ------------------------------------------------------------------
     *  Private helpers
     * ----------------------------------------------------------------*/

    /**
     * Recount approved comments for a post and update its comment_count column.
     */
    private function updatePostCommentCount(int $postId): void
    {
        Database::query(
            "UPDATE posts
             SET comment_count = (
                 SELECT COUNT(*) FROM comments
                 WHERE comments.post_id = :pid AND comments.status = 'approved'
             )
             WHERE id = :id",
            ['pid' => $postId, 'id' => $postId]
        );
    }
}
