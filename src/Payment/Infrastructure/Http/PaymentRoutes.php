<?php

use Illuminate\Support\Facades\Route;
use Payment\Payment\Infrastructure\Http\Controllers\ClientController;
use Payment\Payment\Infrastructure\Http\Controllers\PaymentController;

Route::prefix('client')
    ->group(function () {
       Route::post('/', [ClientController::class, 'store'])->name('client.store');
       Route::get('/{cpfCnpj}', [ClientController::class, 'show'])->name('client.show');
    });

Route::prefix('payment')
    ->group(function () {
        Route::post('/boleto', [PaymentController::class, 'createOrderBoleto'])->name('payment.boleto');
        Route::post('/pix', [PaymentController::class, 'createOrderPix'])->name('payment.pix');
        Route::post('/credit_card', [PaymentController::class, 'createOrderCreditCard'])->name('payment.credit_card');
        Route::post('/confirm/received/{id}', [PaymentController::class, 'confirmReceived'])->name('payment.confirm.received');
    });
