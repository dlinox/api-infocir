<?php

use App\Modules\Learning\Learner\Certification\Http\Controllers\CertificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learner/certifications')->group(function () {
    Route::get('/list', [CertificationController::class, 'list']);
});
