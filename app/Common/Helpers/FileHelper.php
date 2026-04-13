<?php

namespace App\Common\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function generateUniqueFilename(string $name, string $extension): string
    {
        $timestamp = now()->format('YmdHis');
        return "{$name}-{$timestamp}.{$extension}";
    }

    public static function sanitizeFilename(string $caption, string $extension): string
    {
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($caption)));
        $slug = trim($slug, '-');
        $timestamp = now()->format('YmdHis');
        return "{$slug}-{$timestamp}.{$extension}";
    }

    public static function getFileUrl(string $disk, string $filename): string
    {
        return Storage::disk($disk)->url($filename);
    }

    public static function saveFile(string $content, string $filename, string $disk): void
    {
        Storage::disk($disk)->put($filename, $content);
    }

    public static function fileExists(string $disk, string $filename): bool
    {
        return Storage::disk($disk)->exists($filename);
    }

    public static function deleteFile(string $disk, string $filename): bool
    {
        return Storage::disk($disk)->delete($filename);
    }

    public static function getFilePath(string $disk, string $filename): string
    {
        return Storage::disk($disk)->path($filename);
    }

    public static function moveFile(string $fromDisk, string $toDisk, string $oldPath, string $newFolder): string
    {
        $filename = basename($oldPath);
        $newPath = rtrim($newFolder, '/') . '/' . $filename;

        $content = Storage::disk($fromDisk)->get($oldPath);
        Storage::disk($toDisk)->put($newPath, $content);
        Storage::disk($fromDisk)->delete($oldPath);

        return $newPath;
    }

    public static function parseBase64(string $base64): array
    {
        if (preg_match('/^data:([a-zA-Z0-9\/\+\-\.]+);base64,(.+)$/', $base64, $matches)) {
            return [
                'mime_type' => $matches[1],
                'content'   => base64_decode($matches[2], true),
            ];
        }

        return [
            'mime_type' => 'application/octet-stream',
            'content'   => base64_decode($base64, true),
        ];
    }

    public static function mimeToExtension(string $mimeType): string
    {
        $map = [
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/gif'       => 'gif',
            'image/webp'      => 'webp',
            'image/svg+xml'   => 'svg',
            'application/pdf' => 'pdf',
            'text/plain'      => 'txt',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];

        return $map[$mimeType] ?? 'bin';
    }
}
