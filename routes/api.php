<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// M-Pesa API routes
Route::prefix('mpesa')->controller(PaymentController::class)->group(function () {
    Route::post('callback', 'handleMpesaCallback')->name('mpesa.callback');
    Route::post('result', 'handleMpesaCallback')->name('mpesa.result');
});

// Payment API routes
Route::prefix('payment')->controller(PaymentController::class)->group(function () {
    Route::post('initiate', 'initiatePayment')->name('payment.initiate')->middleware('auth:sanctum');
    Route::get('status/{payment}', 'checkPaymentStatusById')->name('payment.status')->middleware('auth:sanctum');
    Route::post('cancel', 'cancelPayment')->name('payment.cancel')->middleware('auth:sanctum');
    Route::get('methods', 'getPaymentMethods')->name('payment.methods');
    Route::post('mpesa/initiate', 'initiateMpesaPayment')->name('payment.mpesa.initiate');
    Route::get('mpesa/status/{transactionId}', 'checkPaymentStatus')->name('payment.mpesa.status');
});
