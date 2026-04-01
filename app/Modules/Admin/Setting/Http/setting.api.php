<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Setting\Http\Controllers\GenderController;
use App\Modules\Admin\Setting\Http\Controllers\DocumentTypeController;
use App\Modules\Admin\Setting\Http\Controllers\InstructionDegreeController;
use App\Modules\Admin\Setting\Http\Controllers\ProfessionController;
use App\Modules\Admin\Setting\Http\Controllers\CompanyTypeController;
use App\Modules\Admin\Setting\Http\Controllers\TrainingLevelController;
use App\Modules\Admin\Setting\Http\Controllers\PositionController;
use App\Modules\Admin\Setting\Http\Controllers\InstitutionTypeController;
use App\Modules\Admin\Setting\Http\Controllers\ProductTypeController;
use App\Modules\Admin\Setting\Http\Controllers\UnitMeasureController;
use App\Modules\Admin\Setting\Http\Controllers\SupplyController;

Route::middleware(['auth:api'])->prefix('/genders')->group(function () {
    Route::post('/data-table', [GenderController::class, 'dataTable'])->name('genders.dataTable');
    Route::post('/save', [GenderController::class, 'save'])->name('genders.save');
    Route::delete('/delete/{id}', [GenderController::class, 'delete'])->name('genders.delete');
    Route::get('/select-items', [GenderController::class, 'getSelectItems'])->name('genders.selectItems');
});

Route::middleware(['auth:api'])->prefix('/document-types')->group(function () {
    Route::post('/data-table', [DocumentTypeController::class, 'dataTable'])->name('document-types.dataTable');
    Route::post('/save', [DocumentTypeController::class, 'save'])->name('document-types.save');
    Route::delete('/delete/{id}', [DocumentTypeController::class, 'delete'])->name('document-types.delete');
    Route::get('/select-items', [DocumentTypeController::class, 'getSelectItems'])->name('document-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/instruction-degrees')->group(function () {
    Route::post('/data-table', [InstructionDegreeController::class, 'dataTable'])->name('instruction-degrees.dataTable');
    Route::post('/save', [InstructionDegreeController::class, 'save'])->name('instruction-degrees.save');
    Route::delete('/delete/{id}', [InstructionDegreeController::class, 'delete'])->name('instruction-degrees.delete');
    Route::get('/select-items', [InstructionDegreeController::class, 'getSelectItems'])->name('instruction-degrees.selectItems');
});

Route::middleware(['auth:api'])->prefix('/professions')->group(function () {
    Route::post('/data-table', [ProfessionController::class, 'dataTable'])->name('professions.dataTable');
    Route::post('/save', [ProfessionController::class, 'save'])->name('professions.save');
    Route::delete('/delete/{id}', [ProfessionController::class, 'delete'])->name('professions.delete');
    Route::get('/select-items', [ProfessionController::class, 'getSelectItems'])->name('professions.selectItems');
});

Route::middleware(['auth:api'])->prefix('/company-types')->group(function () {
    Route::post('/data-table', [CompanyTypeController::class, 'dataTable'])->name('company-types.dataTable');
    Route::post('/save', [CompanyTypeController::class, 'save'])->name('company-types.save');
    Route::delete('/delete/{id}', [CompanyTypeController::class, 'delete'])->name('company-types.delete');
    Route::get('/select-items', [CompanyTypeController::class, 'getSelectItems'])->name('company-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/training-levels')->group(function () {
    Route::post('/data-table', [TrainingLevelController::class, 'dataTable'])->name('training-levels.dataTable');
    Route::post('/save', [TrainingLevelController::class, 'save'])->name('training-levels.save');
    Route::delete('/delete/{id}', [TrainingLevelController::class, 'delete'])->name('training-levels.delete');
    Route::get('/select-items', [TrainingLevelController::class, 'getSelectItems'])->name('training-levels.selectItems');
});

Route::middleware(['auth:api'])->prefix('/positions')->group(function () {
    Route::post('/data-table', [PositionController::class, 'dataTable'])->name('positions.dataTable');
    Route::post('/save', [PositionController::class, 'save'])->name('positions.save');
    Route::delete('/delete/{id}', [PositionController::class, 'delete'])->name('positions.delete');
    Route::get('/select-items', [PositionController::class, 'getSelectItems'])->name('positions.selectItems');
});

Route::middleware(['auth:api'])->prefix('/institution-types')->group(function () {
    Route::post('/data-table', [InstitutionTypeController::class, 'dataTable'])->name('institution-types.dataTable');
    Route::post('/save', [InstitutionTypeController::class, 'save'])->name('institution-types.save');
    Route::delete('/delete/{id}', [InstitutionTypeController::class, 'delete'])->name('institution-types.delete');
    Route::get('/select-items', [InstitutionTypeController::class, 'getSelectItems'])->name('institution-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/product-types')->group(function () {
    Route::post('/data-table', [ProductTypeController::class, 'dataTable'])->name('product-types.dataTable');
    Route::post('/save', [ProductTypeController::class, 'save'])->name('product-types.save');
    Route::delete('/delete/{id}', [ProductTypeController::class, 'delete'])->name('product-types.delete');
    Route::get('/select-items', [ProductTypeController::class, 'getSelectItems'])->name('product-types.selectItems');
});

Route::middleware(['auth:api'])->prefix('/unit-measures')->group(function () {
    Route::post('/data-table', [UnitMeasureController::class, 'dataTable'])->name('unit-measures.dataTable');
    Route::post('/save', [UnitMeasureController::class, 'save'])->name('unit-measures.save');
    Route::delete('/delete/{id}', [UnitMeasureController::class, 'delete'])->name('unit-measures.delete');
    Route::get('/select-items', [UnitMeasureController::class, 'getSelectItems'])->name('unit-measures.selectItems');
});

Route::middleware(['auth:api'])->prefix('/supplies')->group(function () {
    Route::post('/data-table', [SupplyController::class, 'dataTable'])->name('supplies.dataTable');
    Route::post('/save', [SupplyController::class, 'save'])->name('supplies.save');
    Route::delete('/delete/{id}', [SupplyController::class, 'delete'])->name('supplies.delete');
    Route::get('/select-items', [SupplyController::class, 'getSelectItems'])->name('supplies.selectItems');
});
