<?php

namespace Tests\Unit\Entity;

use Payment\Payment\Domain\Entity\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function testShouldCreateEntityFromSingleton(): void
    {
        $entity = Client::fromArray([
            'id' => '1',
            'name' => 'Teste',
            'cpfCnpj' => '000000000000',
        ]);

        $this->assertInstanceOf(Client::class, $entity);
    }
}
