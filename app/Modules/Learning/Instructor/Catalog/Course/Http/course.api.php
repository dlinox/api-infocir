<?php

use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\CourseController;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\CourseModuleController;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\LessonController;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\LessonResourceController;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\QuizQuestionController;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\QuizOptionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/courses')->group(function () {
    Route::post('/data-table', [CourseController::class, 'dataTable']);
    Route::get('/get/{id}', [CourseController::class, 'getById']);
    Route::post('/save', [CourseController::class, 'save']);
    Route::post('/upload-cover/{id}', [CourseController::class, 'uploadCover']);
    Route::delete('/delete/{id}', [CourseController::class, 'delete']);
    Route::get('/select-items', [CourseController::class, 'getSelectItems']);

    Route::prefix('/modules')->group(function () {
        Route::post('/save', [CourseModuleController::class, 'save']);
        Route::delete('/delete/{id}', [CourseModuleController::class, 'delete']);
    });

    Route::prefix('/lessons')->group(function () {
        Route::post('/save', [LessonController::class, 'save']);
        Route::patch('/update-has-quiz/{id}', [LessonController::class, 'updateHasQuiz']);
        Route::delete('/delete/{id}', [LessonController::class, 'delete']);
    });

    Route::prefix('/lesson-resources')->group(function () {
        Route::post('/save', [LessonResourceController::class, 'save']);
        Route::delete('/delete/{id}', [LessonResourceController::class, 'delete']);
    });

    Route::prefix('/quiz-questions')->group(function () {
        Route::post('/save', [QuizQuestionController::class, 'save']);
        Route::delete('/delete/{id}', [QuizQuestionController::class, 'delete']);
    });

    Route::prefix('/quiz-options')->group(function () {
        Route::post('/save', [QuizOptionController::class, 'save']);
        Route::delete('/delete/{id}', [QuizOptionController::class, 'delete']);
    });
});
