<?php

namespace App\Common\Services\Actions;

use App\Common\Helpers\FileHelper;
use App\Models\Core\CoreFile;
use Illuminate\Http\UploadedFile;

class CreateFileAction
{
    public function execute(UploadedFile $file, ?string $caption = null): CoreFile
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $caption
            ? FileHelper::sanitizeFilename($caption, $extension)
            : FileHelper::generateUniqueFilename('file', $extension);
        $filepath = now()->format('Y/m/d') . '/' . $filename;

        FileHelper::saveFile($file->get(), $filepath, 'temp');

        return CoreFile::create([
            'storage_disk' => 'temp',
            'filename'     => $filename,
            'filepath'     => $filepath,
            'mime_type'    => $file->getMimeType(),
            'caption'      => $caption,
            'size'         => $file->getSize(),
        ]);
    }
}
