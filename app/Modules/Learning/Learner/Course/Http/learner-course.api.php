<?php

use App\Modules\Learning\Learner\Course\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.session'])->prefix('/learning/learner/courses')->group(function () {
    Route::get('/catalog', [CourseController::class, 'catalog']);
    Route::get('/content/{enrollmentId}', [CourseController::class, 'content']);
});
