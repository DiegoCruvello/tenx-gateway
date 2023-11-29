<?php

namespace Tests\Unit\DTO;

use Payment\Payment\Application\DTO\InputCreateClient;
use Tests\TestCase;

class InputCreateClientTest extends TestCase
{
    public function testShouldReturnDataInArray(): void
    {
        $dto = InputCreateClient::fromArray([
            'name' => 'teste teste',
            'cpf' => '00000000000',
        ]);

        $this->assertInstanceOf(InputCreateClient::class, $dto);
        $this->assertIsArray($dto->toArray());
    }
}
