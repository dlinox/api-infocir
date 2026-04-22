<?php

use App\Modules\Learning\Learner\Enrollment\Http\Controllers\EnrollmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learning/learner/enrollments')->group(function () {
    Route::get('/list', [EnrollmentController::class, 'list']);
    Route::get('/get/{id}', [EnrollmentController::class, 'getById']);
    Route::post('/self-enroll', [EnrollmentController::class, 'selfEnroll']);
});
