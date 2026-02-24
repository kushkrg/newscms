<?php

namespace App\Core;

class Uploader
{
    private array $config;
    private array $errors = [];

    public function __construct()
    {
        $cfg = require CONFIG_PATH . '/config.php';
        $this->config = $cfg['upload'];
    }

    public function upload(array $file, string $directory = 'images'): ?array
    {
        $this->errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'Upload failed with error code: ' . $file['error'];
            return null;
        }

        // Check file size
        if ($file['size'] > $this->config['max_size']) {
            $this->errors[] = 'File size exceeds maximum allowed size of ' . ($this->config['max_size'] / 1024 / 1024) . 'MB';
            return null;
        }

        // Check mime type with finfo (magic bytes)
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $this->config['allowed_types'], true)) {
            $this->errors[] = 'File type not allowed: ' . $mime;
            return null;
        }

        // Generate unique filename
        $ext = $this->getExtension($mime);
        $filename = uniqid('img_', true) . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        // Ensure directories exist
        $basePath = UPLOAD_PATH . '/' . $directory;
        foreach (['original', 'medium', 'thumb'] as $dir) {
            $path = $basePath . '/' . $dir;
            if (!is_dir($path)) @mkdir($path, 0755, true);
        }

        $originalPath = $basePath . '/original/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
            $this->errors[] = 'Failed to move uploaded file.';
            return null;
        }

        // Get image dimensions
        $imageInfo = @getimagesize($originalPath);
        $width = $imageInfo[0] ?? 0;
        $height = $imageInfo[1] ?? 0;

        // Create resized versions
        $mediumPath = null;
        $thumbPath = null;

        if ($imageInfo) {
            $mediumPath = $this->resize($originalPath, $basePath . '/medium/' . $filename, $this->config['image_sizes']['medium']);
            $thumbPath = $this->resize($originalPath, $basePath . '/thumb/' . $filename, $this->config['image_sizes']['thumb']);
        }

        return [
            'filename'      => $filename,
            'original_name' => $file['name'],
            'mime_type'     => $mime,
            'file_size'     => $file['size'],
            'width'         => $width,
            'height'        => $height,
            'path_original' => $directory . '/original/' . $filename,
            'path_medium'   => $mediumPath ? $directory . '/medium/' . $filename : null,
            'path_thumb'    => $thumbPath ? $directory . '/thumb/' . $filename : null,
        ];
    }

    public function uploadAvatar(array $file): ?string
    {
        $this->errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            $this->errors[] = 'Invalid avatar image type.';
            return null;
        }

        $ext = $this->getExtension($mime);
        $filename = 'avatar_' . uniqid() . '.' . $ext;
        $destPath = UPLOAD_PATH . '/avatars/' . $filename;

        if (!is_dir(UPLOAD_PATH . '/avatars')) {
            @mkdir(UPLOAD_PATH . '/avatars', 0755, true);
        }

        // Resize to 200x200
        $this->resize($file['tmp_name'], $destPath, 200, true);

        return 'avatars/' . $filename;
    }

    private function resize(string $source, string $dest, int $maxWidth, bool $square = false): ?string
    {
        $info = @getimagesize($source);
        if (!$info) return null;

        [$origW, $origH] = $info;
        $mime = $info['mime'];

        $srcImage = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png'  => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            'image/gif'  => imagecreatefromgif($source),
            default      => null,
        };

        if (!$srcImage) return null;

        if ($square) {
            $size = min($origW, $origH);
            $srcX = (int) (($origW - $size) / 2);
            $srcY = (int) (($origH - $size) / 2);
            $newImage = imagecreatetruecolor($maxWidth, $maxWidth);
            imagecopyresampled($newImage, $srcImage, 0, 0, $srcX, $srcY, $maxWidth, $maxWidth, $size, $size);
        } else {
            if ($origW <= $maxWidth) {
                imagedestroy($srcImage);
                copy($source, $dest);
                return $dest;
            }
            $ratio = $maxWidth / $origW;
            $newW = $maxWidth;
            $newH = (int) ($origH * $ratio);
            $newImage = imagecreatetruecolor($newW, $newH);

            // Preserve transparency for PNG
            if ($mime === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }

            imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        }

        $result = match ($mime) {
            'image/jpeg' => imagejpeg($newImage, $dest, 85),
            'image/png'  => imagepng($newImage, $dest, 8),
            'image/webp' => imagewebp($newImage, $dest, 85),
            'image/gif'  => imagegif($newImage, $dest),
            default      => false,
        };

        imagedestroy($srcImage);
        imagedestroy($newImage);

        return $result ? $dest : null;
    }

    private function getExtension(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'bin',
        };
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public static function deleteFile(string $path): void
    {
        $full = UPLOAD_PATH . '/' . $path;
        if (file_exists($full)) @unlink($full);
    }
}
