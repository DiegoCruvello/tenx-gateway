<?php

namespace Tests\Unit\DTO;

use Payment\Payment\Application\DTO\InputCreateOrder;
use Tests\TestCase;

class InputCreateOrderTest extends TestCase
{
    public function testShouldReturnDataInArray(): void
    {
        $dto = InputCreateOrder::fromArray([
            'billingType' => 'BOLETO',
            'customer' => '111111111111',
            'value' => 100.3,
            'dueDate' => '2023-11-11',
        ]);

        $this->assertInstanceOf(InputCreateOrder::class, $dto);
        $this->assertIsArray($dto->toArray());
    }
}
