<?php

namespace Payment\Payment\Domain\Entity;

readonly class CreditCard
{
    public function __construct(
        public string $id,
        public string $customer,
        public string $status,
        public string $transactionReceiptUrl,
        public string $invoiceUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'customer' => $this->customer,
          'status' => $this->status,
          'transactionReceiptUrl' => $this->transactionReceiptUrl,
          'invoiceUrl' => $this->invoiceUrl,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['customer'],
            $data['status'],
            $data['transactionReceiptUrl'],
            $data['invoiceUrl'],
        );
    }
}
