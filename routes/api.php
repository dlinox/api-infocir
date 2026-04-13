<?php

use Illuminate\Support\Facades\Route;
use App\Common\Http\Controllers\FileController;

Route::middleware(['auth:api'])->prefix('/files')->group(function () {
    Route::post('/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::post('/upload-base64', [FileController::class, 'uploadBase64'])->name('files.uploadBase64');
    Route::delete('/delete/{id}', [FileController::class, 'delete'])->name('files.delete');
});
