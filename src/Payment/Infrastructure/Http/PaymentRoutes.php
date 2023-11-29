<?php

use Illuminate\Support\Facades\Route;
use Payment\Payment\Infrastructure\Http\Controllers\ClientController;
use Payment\Payment\Infrastructure\Http\Controllers\PaymentController;

Route::prefix('client')
    ->group(function () {
       Route::post('/', [ClientController::class, 'store']);
       Route::get('/{cpfCnpj}', [ClientController::class, 'show']);
    });

Route::prefix('payment')
    ->group(function () {
        Route::post('/boleto', [PaymentController::class, 'createOrderBoleto']);
        Route::post('/pix', [PaymentController::class, 'createOrderPix']);
        Route::post('/credit_card', [PaymentController::class, 'createOrderCreditCard']);
        Route::post('/confirm/received/{id}', [PaymentController::class, 'confirmReceived']);
    });
