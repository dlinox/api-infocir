<?php

namespace App\Common\Services\Actions;

use App\Common\Helpers\FileHelper;
use App\Models\Core\CoreFile;

class CreateFileFromBase64Action
{
    public function execute(string $base64, ?string $caption = null): CoreFile
    {
        $parsed = FileHelper::parseBase64($base64);
        $extension = FileHelper::mimeToExtension($parsed['mime_type']);
        $filename = $caption
            ? FileHelper::sanitizeFilename($caption, $extension)
            : FileHelper::generateUniqueFilename('file', $extension);
        $filepath = now()->format('Y/m/d') . '/' . $filename;

        FileHelper::saveFile($parsed['content'], $filepath, 'temp');

        return CoreFile::create([
            'storage_disk' => 'temp',
            'filename'     => $filename,
            'filepath'     => $filepath,
            'mime_type'    => $parsed['mime_type'],
            'caption'      => $caption,
            'size'         => strlen($parsed['content']),
        ]);
    }
}
