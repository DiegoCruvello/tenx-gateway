<?php

namespace Payment\Payment\Infrastructure\Client;

use GuzzleHttp\Client;

class Asaas
{
    public function __construct(
        public Client $client
    ) {
    }

    public function getAsaasClient(): Client
    {
        return $this->client;
    }
}
