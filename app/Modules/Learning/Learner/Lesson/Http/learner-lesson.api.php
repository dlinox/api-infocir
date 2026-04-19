<?php

use App\Modules\Learning\Learner\Lesson\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learner/lessons')->group(function () {
    Route::post('/complete', [LessonController::class, 'complete']);
    Route::post('/submit-quiz', [LessonController::class, 'submitQuiz']);
});
