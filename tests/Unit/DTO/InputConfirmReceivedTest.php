<?php

namespace Tests\Unit\DTO;

use Payment\Payment\Application\DTO\InputConfirmReceived;
use Tests\TestCase;

class InputConfirmReceivedTest extends TestCase
{
    public function testShouldReturnDataInArray(): void
    {
        $dto = InputConfirmReceived::fromArray([
            'paymentDate' => '2023-11-29',
            'value' => 10.2,
        ]);

        $this->assertInstanceOf(InputConfirmReceived::class, $dto);
        $this->assertIsArray($dto->toArray());
    }
}
