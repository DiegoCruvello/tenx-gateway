<?php

namespace Payment\Payment\Application\DTO;

class InputCreateOrder
{
    public function __construct(
        public string $billingType,
        public string $customer,
        public float $value,
        public string $dueDate,
        public ?array $creditCard,
        public ?array $creditCardHolderInfo,
    ) {
    }

    public function toArray(): array
    {
        return [
            'billingType' => $this->billingType,
            'customer' => $this->customer,
            'value' => $this->value,
            'dueDate' => $this->dueDate,
            'creditCard' => $this->creditCard,
            'creditCardHolderInfo' => $this->creditCardHolderInfo,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['billingType'],
            $data['customer'],
            $data['value'],
            $data['dueDate'],
            $data['creditCard'] ?? null,
            $data['creditCardHolderInfo'] ?? null,
        );
    }
}
