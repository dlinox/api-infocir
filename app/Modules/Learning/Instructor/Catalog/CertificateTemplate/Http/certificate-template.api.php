<?php

use App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Controllers\CertificateTemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/certificate-templates')->group(function () {
    Route::post('/data-table', [CertificateTemplateController::class, 'dataTable']);
    Route::get('/select-items', [CertificateTemplateController::class, 'getSelectItems']);
    Route::get('/get/{templateId}', [CertificateTemplateController::class, 'getById']);
    Route::get('/get-by-entity/{entityType}/{entityId}', [CertificateTemplateController::class, 'getByEntity'])
        ->whereIn('entityType', ['course', 'program', 'training']);
    Route::post('/save', [CertificateTemplateController::class, 'save']);
    Route::post('/update/{templateId}', [CertificateTemplateController::class, 'updateTemplate']);
    Route::post('/upload-background/{entityType}/{entityId}', [CertificateTemplateController::class, 'uploadBackground'])
        ->whereIn('entityType', ['course', 'program', 'training']);
    Route::post('/upload-background-template/{templateId}', [CertificateTemplateController::class, 'uploadBackgroundForTemplate']);
});
