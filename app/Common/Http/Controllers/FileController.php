<?php

namespace App\Common\Http\Controllers;

use App\Common\Http\Requests\File\UploadBase64FileRequest;
use App\Common\Http\Requests\File\UploadFileRequest;
use App\Common\Http\Resources\File\FileResource;
use App\Common\Http\Responses\ApiResponse;
use App\Common\Services\FileService;

class FileController
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function upload(UploadFileRequest $request)
    {
        $file = $this->fileService->upload(
            $request->file('file'),
            $request->validated('caption'),
        );

        return ApiResponse::success(
            new FileResource($file),
            'Archivo subido correctamente',
        );
    }

    public function uploadBase64(UploadBase64FileRequest $request)
    {
        $file = $this->fileService->uploadBase64(
            $request->validated('file'),
            $request->validated('caption'),
        );

        return ApiResponse::success(
            new FileResource($file),
            'Archivo subido correctamente',
        );
    }

    public function delete(int $id)
    {
        $this->fileService->delete($id);

        return ApiResponse::success(null, 'Archivo eliminado correctamente');
    }
}
