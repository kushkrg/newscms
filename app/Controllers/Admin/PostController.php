<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\Sanitizer;
use App\Core\Session;
use App\Core\Uploader;
use App\Core\Validator;
use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\Tag;

class PostController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/posts
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $filters = [
            'status'      => $request->get('status', ''),
            'category_id' => $request->get('category_id', ''),
            'search'      => $request->get('search', ''),
        ];

        $page       = max(1, (int) $request->get('page', 1));
        $perPage    = 15;
        $total      = Post::adminCount($filters);
        $paginator  = new Paginator($total, $perPage, $page);
        $posts      = Post::adminList($filters, $paginator->perPage, $paginator->offset);
        $categories = Category::all();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/posts/index', [
            'pageTitle'   => 'Posts',
            'currentPage' => 'posts',
            'posts'      => $posts,
            'categories' => $categories,
            'filters'    => $filters,
            'paginator'  => $paginator,
        ]);
    }

    /* ------------------------------------------------------------------
     *  CREATE  --  GET /admin/posts/create
     * ----------------------------------------------------------------*/

    public function create(Request $request, array $params): void
    {
        $categories = Category::all();
        $tags       = Tag::all();

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/posts/create', [
            'pageTitle'   => 'Create Post',
            'currentPage' => 'posts',
            'categories' => $categories,
            'tags'       => $tags,
        ]);
    }

    /* ------------------------------------------------------------------
     *  STORE  --  POST /admin/posts
     * ----------------------------------------------------------------*/

    public function store(Request $request, array $params): void
    {
        Csrf::check();

        $data = [
            'title'          => trim((string) $request->post('title', '')),
            'content'        => (string) $request->post('content', ''),
            'excerpt'        => trim((string) $request->post('excerpt', '')),
            'category_id'    => $request->post('category_id') ?: null,
            'status'         => $request->post('status', 'draft'),
            'meta_title'     => trim((string) $request->post('meta_title', '')),
            'meta_description' => trim((string) $request->post('meta_description', '')),
            'is_featured'    => $request->post('is_featured') ? 1 : 0,
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'title'   => ['required', 'max:300'],
            'content' => ['required'],
            'status'  => ['in:draft,published,scheduled'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Session::flash('old', $data);
            Response::redirect(url('admin/posts/create'));
        }

        // Slug
        $slug = Sanitizer::slug($data['title']);
        $slug = Sanitizer::uniqueSlug($slug, 'posts');

        // Reading time
        $readingTimeMins = Sanitizer::readingTime($data['content']);

        // Auto-generate excerpt if empty
        if ($data['excerpt'] === '') {
            $data['excerpt'] = Sanitizer::excerpt($data['content']);
        }

        // Clean HTML content
        $data['content'] = Sanitizer::cleanHtml($data['content']);

        // Handle featured image upload
        $featuredImage = null;
        $file = $request->file('featured_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploader = new Uploader();
            $uploadResult = $uploader->upload($file, 'posts');
            if ($uploadResult) {
                $featuredImage = $uploadResult['path_original'];
            }
        }

        // Handle downloadable file
        $downloadFile = null;
        $downloadFileName = null;
        $dlFile = $request->file('download_file');
        if ($dlFile && $dlFile['error'] === UPLOAD_ERR_OK) {
            $dlResult = $this->uploadDownloadFile($dlFile);
            if ($dlResult) {
                $downloadFile = $dlResult['path'];
                $downloadFileName = $dlResult['original_name'];
            }
        } elseif ($request->post('download_file_media', '') !== '') {
            $mediaPath = trim((string) $request->post('download_file_media', ''));
            $downloadFile = $mediaPath;
            $downloadFileName = basename($mediaPath);
        }

        // Published timestamp
        $publishedAt = null;
        if ($data['status'] === 'published') {
            $publishedAt = $request->post('published_at') ?: date('Y-m-d H:i:s');
        }

        // Build insert data
        $postData = [
            'title'            => $data['title'],
            'slug'             => $slug,
            'excerpt'          => $data['excerpt'],
            'content'          => $data['content'],
            'category_id'      => $data['category_id'],
            'user_id'          => Auth::id(),
            'status'           => $data['status'],
            'is_featured'      => $data['is_featured'],
            'is_sticky'        => $request->post('is_sticky') ? 1 : 0,
            'allow_comments'   => $request->post('allow_comments') ? 1 : 0,
            'featured_image'   => $featuredImage,
            'featured_image_alt' => trim((string) $request->post('featured_image_alt', '')),
            'meta_title'       => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'canonical_url'    => trim((string) $request->post('canonical_url', '')) ?: null,
            'schema_type'      => $request->post('schema_type', 'Article'),
            'reading_time_mins' => $readingTimeMins,
            'published_at'     => $publishedAt,
            'download_file'    => $downloadFile,
            'download_file_name' => $downloadFileName,
        ];

        $postId = Post::create($postData);

        // Sync tags (comma-separated names)
        $tagNames = array_filter(array_map('trim', explode(',', (string) $request->post('tags', ''))));
        if (!empty($tagNames)) {
            $tagIds = [];
            foreach ($tagNames as $name) {
                if ($name !== '') {
                    $tagIds[] = Tag::findOrCreate($name);
                }
            }
            Post::syncTags($postId, $tagIds);
        }

        // Save initial revision
        $this->saveRevision($postId, $postData);

        // Update category post count
        if ($data['category_id']) {
            Category::updatePostCount((int) $data['category_id']);
        }

        Session::flash('success', 'Post created successfully.');
        Response::redirect(url('admin/posts'));
    }

    /* ------------------------------------------------------------------
     *  EDIT  --  GET /admin/posts/{id}/edit
     * ----------------------------------------------------------------*/

    public function edit(Request $request, array $params): void
    {
        $post = Post::find((int) $params['id']);
        if (!$post) {
            Session::flash('error', 'Post not found.');
            Response::redirect(url('admin/posts'));
        }

        $categories = Category::all();
        $tags       = Tag::all();
        $postTags   = Post::getTags((int) $params['id']);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/posts/edit', [
            'pageTitle'   => 'Edit Post',
            'currentPage' => 'posts',
            'post'       => $post,
            'categories' => $categories,
            'tags'       => $tags,
            'postTags'   => $postTags,
        ]);
    }

    /* ------------------------------------------------------------------
     *  UPDATE  --  POST /admin/posts/{id}
     * ----------------------------------------------------------------*/

    public function update(Request $request, array $params): void
    {
        Csrf::check();

        $postId = (int) $params['id'];
        $post   = Post::find($postId);
        if (!$post) {
            Session::flash('error', 'Post not found.');
            Response::redirect(url('admin/posts'));
        }

        $data = [
            'title'          => trim((string) $request->post('title', '')),
            'content'        => (string) $request->post('content', ''),
            'excerpt'        => trim((string) $request->post('excerpt', '')),
            'category_id'    => $request->post('category_id') ?: null,
            'status'         => $request->post('status', 'draft'),
            'meta_title'     => trim((string) $request->post('meta_title', '')),
            'meta_description' => trim((string) $request->post('meta_description', '')),
            'is_featured'    => $request->post('is_featured') ? 1 : 0,
        ];

        // Validation
        $validator = new Validator($data);
        $valid = $validator->validate([
            'title'   => ['required', 'max:300'],
            'content' => ['required'],
            'status'  => ['in:draft,published,scheduled'],
        ]);

        if (!$valid) {
            Session::flash('error', $validator->firstError());
            Response::redirect(url("admin/posts/{$postId}/edit"));
        }

        // Handle slug changes -- create redirect from old slug to new
        $newSlug = trim((string) $request->post('slug', ''));
        if ($newSlug !== '' && $newSlug !== $post['slug']) {
            $newSlug = Sanitizer::slug($newSlug);
            $newSlug = Sanitizer::uniqueSlug($newSlug, 'posts', $postId);

            // Create 301 redirect from the old URL to the new one
            Redirect::create([
                'from_path' => '/' . $post['slug'],
                'to_url'    => url($newSlug),
                'type'      => 301,
            ]);
        } else {
            $newSlug = $post['slug'];
        }

        // Recalculate reading time
        $readingTimeMins = Sanitizer::readingTime($data['content']);

        // Auto-generate excerpt if empty
        if ($data['excerpt'] === '') {
            $data['excerpt'] = Sanitizer::excerpt($data['content']);
        }

        // Clean HTML content
        $data['content'] = Sanitizer::cleanHtml($data['content']);

        // Handle featured image upload
        $featuredImage = $post['featured_image'];
        $file = $request->file('featured_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploader = new Uploader();
            $uploadResult = $uploader->upload($file, 'posts');
            if ($uploadResult) {
                $featuredImage = $uploadResult['path_original'];
            }
        }

        // Handle downloadable file
        $downloadFile = $post['download_file'] ?? null;
        $downloadFileName = $post['download_file_name'] ?? null;
        // Remove file if requested
        if ($request->post('remove_download_file') === '1') {
            $downloadFile = null;
            $downloadFileName = null;
        }
        $dlFile = $request->file('download_file');
        if ($dlFile && $dlFile['error'] === UPLOAD_ERR_OK) {
            $dlResult = $this->uploadDownloadFile($dlFile);
            if ($dlResult) {
                $downloadFile = $dlResult['path'];
                $downloadFileName = $dlResult['original_name'];
            }
        } elseif ($request->post('download_file_media', '') !== '') {
            $mediaPath = trim((string) $request->post('download_file_media', ''));
            $downloadFile = $mediaPath;
            $downloadFileName = basename($mediaPath);
        }

        // Published timestamp
        $publishedAt = $post['published_at'];
        if ($data['status'] === 'published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }
        // Allow explicit published_at override
        $explicitPublishedAt = $request->post('published_at');
        if ($explicitPublishedAt) {
            $publishedAt = $explicitPublishedAt;
        }

        // Build update data
        $updateData = [
            'title'            => $data['title'],
            'slug'             => $newSlug,
            'excerpt'          => $data['excerpt'],
            'content'          => $data['content'],
            'category_id'      => $data['category_id'],
            'status'           => $data['status'],
            'is_featured'      => $data['is_featured'],
            'is_sticky'        => $request->post('is_sticky') ? 1 : 0,
            'allow_comments'   => $request->post('allow_comments') ? 1 : 0,
            'featured_image'   => $featuredImage,
            'featured_image_alt' => trim((string) $request->post('featured_image_alt', '')),
            'meta_title'       => $data['meta_title'] ?: null,
            'meta_description' => $data['meta_description'] ?: null,
            'canonical_url'    => trim((string) $request->post('canonical_url', '')) ?: null,
            'schema_type'      => $request->post('schema_type', 'Article'),
            'reading_time_mins' => $readingTimeMins,
            'published_at'     => $publishedAt,
            'download_file'    => $downloadFile,
            'download_file_name' => $downloadFileName,
        ];

        Post::update($postId, $updateData);

        // Sync tags (comma-separated names)
        $tagNames = array_filter(array_map('trim', explode(',', (string) $request->post('tags', ''))));
        $tagIds   = [];
        foreach ($tagNames as $name) {
            if ($name !== '') {
                $tagIds[] = Tag::findOrCreate($name);
            }
        }
        Post::syncTags($postId, $tagIds);

        // Save revision (keep last 5)
        $this->saveRevision($postId, $updateData);
        $this->pruneRevisions($postId, 5);

        // Update category post counts (old and new)
        if ($post['category_id']) {
            Category::updatePostCount((int) $post['category_id']);
        }
        if ($data['category_id'] && (int) $data['category_id'] !== (int) $post['category_id']) {
            Category::updatePostCount((int) $data['category_id']);
        }

        Session::flash('success', 'Post updated successfully.');
        Response::redirect(url('admin/posts'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/posts/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $postId = (int) $params['id'];
        $post   = Post::find($postId);
        if (!$post) {
            Session::flash('error', 'Post not found.');
            Response::redirect(url('admin/posts'));
        }

        $categoryId = $post['category_id'];

        Post::delete($postId);

        // Also remove revisions
        Database::query("DELETE FROM post_revisions WHERE post_id = :post_id", ['post_id' => $postId]);

        // Update category post count
        if ($categoryId) {
            Category::updatePostCount((int) $categoryId);
        }

        Session::flash('success', 'Post deleted successfully.');
        Response::redirect(url('admin/posts'));
    }

    /* ------------------------------------------------------------------
     *  Private helpers
     * ----------------------------------------------------------------*/

    /**
     * Save a revision snapshot of the post.
     */
    private function saveRevision(int $postId, array $data): void
    {
        Database::query(
            "INSERT INTO post_revisions (post_id, title, content, excerpt, user_id, created_at)
             VALUES (:post_id, :title, :content, :excerpt, :user_id, NOW())",
            [
                'post_id' => $postId,
                'title'   => $data['title'] ?? '',
                'content' => $data['content'] ?? '',
                'excerpt' => $data['excerpt'] ?? '',
                'user_id' => Auth::id(),
            ]
        );
    }

    /**
     * Keep only the N most recent revisions for a post, deleting older ones.
     */
    private function pruneRevisions(int $postId, int $keep): void
    {
        $revisions = Database::query(
            "SELECT id FROM post_revisions WHERE post_id = :post_id ORDER BY created_at DESC",
            ['post_id' => $postId]
        )->fetchAll();

        $toDelete = array_slice($revisions, $keep);

        foreach ($toDelete as $rev) {
            Database::query(
                "DELETE FROM post_revisions WHERE id = :id",
                ['id' => $rev['id']]
            );
        }
    }

    /**
     * Upload a downloadable file (PDF, ZIP, etc.) and return path info.
     */
    private function uploadDownloadFile(array $file): ?array
    {
        $allowedMimes = [
            'application/pdf',
            'application/zip',
            'application/x-zip-compressed',
            'application/x-rar-compressed',
            'application/vnd.rar',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
        $maxSize = 50 * 1024 * 1024; // 50MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        if ($file['size'] > $maxSize) {
            Session::flash('error', 'Download file too large. Maximum 50MB allowed.');
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $allowedMimes, true)) {
            Session::flash('error', 'File type not allowed: ' . $mime);
            return null;
        }

        $dir = UPLOAD_PATH . '/downloads';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('dl_', true) . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return null;
        }

        return [
            'path'          => 'downloads/' . $filename,
            'original_name' => $file['name'],
        ];
    }
}
