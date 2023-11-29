<?php

namespace Payment\Payment\Infrastructure\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Payment\Payment\Infrastructure\Client\Asaas;

class PaymentProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/PaymentRoutes.php');

        $this->app->bind(
            Asaas::class,
            fn() => new Asaas(
                new Client([
                    'base_uri' => config('services.client_asaas.base_uri'),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'access_token' => config('services.client_asaas.api_key')
                    ]
                ])
            )
        );
    }
}
