<?php

use App\Modules\Learning\Instructor\Scheduling\Certification\Http\Controllers\CertificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/certifications')->group(function () {
    Route::post('/data-table', [CertificationController::class, 'dataTable']);
});
