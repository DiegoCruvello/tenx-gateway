<?php

use Illuminate\Support\Facades\Route;
use Payment\Payment\Infrastructure\Http\Controllers\ClientController;

Route::prefix('client')
    ->group(function () {
       Route::post('/', [ClientController::class, 'store']);
       Route::get('/{cpfCnpj}', [ClientController::class, 'show']);
    });
