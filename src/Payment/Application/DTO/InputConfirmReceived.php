<?php

namespace Payment\Payment\Application\DTO;

class InputConfirmReceived
{
    public function __construct(
        public string $paymentDate,
        public float $value,
    ) {
    }

    public function toArray(): array
    {
        return [
            'paymentDate' => $this->paymentDate,
            'value' => $this->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['paymentDate'],
            $data['value'],
        );
    }
}
