<?php

namespace Payment\Payment\Domain\Entity;

readonly class Client
{
    public function __construct(
        public string $id,
        public string $name,
        public string $cpf,
    ) {
    }

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'cpf' => $this->cpf,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
          $data['id'],
          $data['name'],
          $data['cpfCnpj'],
        );
    }
}
