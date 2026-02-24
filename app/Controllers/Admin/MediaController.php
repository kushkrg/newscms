<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Paginator;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Core\Uploader;
use App\Core\View;
use App\Models\Media;

class MediaController
{
    /**
     * Run before every action -- require a logged-in user.
     */
    public function middleware(): void
    {
        Auth::requireAuth();
    }

    /* ------------------------------------------------------------------
     *  INDEX  --  GET /admin/media
     * ----------------------------------------------------------------*/

    public function index(Request $request, array $params): void
    {
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = 24;
        $total   = Media::count();

        $paginator = new Paginator($total, $perPage, $page);
        $media     = Media::all($paginator->perPage, $paginator->offset);

        $view = new View();
        $view->setLayout('layouts/admin');
        echo $view->render('admin/media/index', [
            'pageTitle'   => 'Media Library',
            'currentPage' => 'media',
            'media'     => $media,
            'paginator' => $paginator,
        ]);
    }

    /* ------------------------------------------------------------------
     *  UPLOAD  --  POST /admin/media/upload
     * ----------------------------------------------------------------*/

    public function upload(Request $request, array $params): void
    {
        Csrf::check();

        $file = $request->file('file');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'No file was uploaded or an upload error occurred.');
            Response::redirect(url('admin/media'));
        }

        $uploader     = new Uploader();
        $uploadResult = $uploader->upload($file, 'media');

        if (!$uploadResult) {
            $errors = $uploader->errors();
            Session::flash('error', $errors[0] ?? 'File upload failed.');
            Response::redirect(url('admin/media'));
        }

        // Save to media table
        $altText = trim((string) $request->post('alt_text', ''));

        $mediaData = [
            'filename'      => $uploadResult['filename'],
            'original_name' => $uploadResult['original_name'],
            'mime_type'     => $uploadResult['mime_type'],
            'file_size'     => $uploadResult['file_size'],
            'width'         => $uploadResult['width'],
            'height'        => $uploadResult['height'],
            'path_original' => $uploadResult['path_original'],
            'path_medium'   => $uploadResult['path_medium'],
            'path_thumb'    => $uploadResult['path_thumb'],
            'alt_text'      => $altText ?: null,
            'user_id'       => Auth::id(),
        ];

        Media::create($mediaData);

        Session::flash('success', 'File uploaded successfully.');
        Response::redirect(url('admin/media'));
    }

    /* ------------------------------------------------------------------
     *  DELETE  --  POST /admin/media/{id}/delete
     * ----------------------------------------------------------------*/

    public function delete(Request $request, array $params): void
    {
        Csrf::check();

        $mediaId = (int) $params['id'];
        $media   = Media::find($mediaId);
        if (!$media) {
            Session::flash('error', 'Media not found.');
            Response::redirect(url('admin/media'));
        }

        // Delete physical files from disk
        if (!empty($media['path_original'])) {
            Uploader::deleteFile($media['path_original']);
        }
        if (!empty($media['path_medium'])) {
            Uploader::deleteFile($media['path_medium']);
        }
        if (!empty($media['path_thumb'])) {
            Uploader::deleteFile($media['path_thumb']);
        }

        // Delete database record
        Media::delete($mediaId);

        Session::flash('success', 'Media deleted successfully.');
        Response::redirect(url('admin/media'));
    }

    /* ------------------------------------------------------------------
     *  JSON  --  GET /admin/media/json  (for media picker in post forms)
     * ----------------------------------------------------------------*/

    public function json(Request $request, array $params): void
    {
        $media = Media::all(200, 0);

        $items = [];
        foreach ($media as $m) {
            $items[] = [
                'id'    => (int) $m['id'],
                'name'  => $m['original_name'] ?? $m['filename'],
                'path'  => $m['path_original'] ?? '',
                'thumb' => !empty($m['path_thumb']) ? upload_url($m['path_thumb']) : '',
                'url'   => !empty($m['path_original']) ? upload_url($m['path_original']) : '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}
