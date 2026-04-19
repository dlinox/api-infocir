<?php

use App\Modules\Learning\Learner\Course\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learner/courses')->group(function () {
    Route::get('/content/{enrollmentId}', [CourseController::class, 'content']);
});
