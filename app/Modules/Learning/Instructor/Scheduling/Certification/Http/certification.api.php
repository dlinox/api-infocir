<?php

use App\Modules\Learning\Instructor\Scheduling\Certification\Http\Controllers\CertificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learning/instructor/certifications')->group(function () {
    Route::post('/data-table', [CertificationController::class, 'dataTable']);
});
