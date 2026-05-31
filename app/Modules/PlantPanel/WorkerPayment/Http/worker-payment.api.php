<?php

use App\Modules\PlantPanel\WorkerPayment\Http\Controllers\WorkerPaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.session'])->prefix('/plant-panel/worker-payments')->group(function () {
    Route::post('/data-table', [WorkerPaymentController::class, 'dataTable'])->name('plantPanel.workerPayments.dataTable');
    Route::get('/get/{id}', [WorkerPaymentController::class, 'getById'])->name('plantPanel.workerPayments.getById');
    Route::post('/save', [WorkerPaymentController::class, 'save'])->name('plantPanel.workerPayments.save');
    Route::get('/summarize-period', [WorkerPaymentController::class, 'summarizePeriod'])->name('plantPanel.workerPayments.summarizePeriod');
    Route::get('/worker-select-items', [WorkerPaymentController::class, 'workerSelectItems'])->name('plantPanel.workerPayments.workerSelectItems');
    Route::patch('/mark-as-paid/{id}', [WorkerPaymentController::class, 'markAsPaid'])->name('plantPanel.workerPayments.markAsPaid');
    Route::patch('/cancel/{id}', [WorkerPaymentController::class, 'cancel'])->name('plantPanel.workerPayments.cancel');
    Route::patch('/pay-current-period/{workerPersonId}', [WorkerPaymentController::class, 'payCurrentPeriod'])->name('plantPanel.workerPayments.payCurrentPeriod');
});
