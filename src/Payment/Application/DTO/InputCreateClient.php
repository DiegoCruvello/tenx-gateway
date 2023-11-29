<?php

namespace Payment\Payment\Application\DTO;

readonly class InputCreateClient
{
    public function __construct(
        public string $name,
        public string $cpfCnpj,
    ) {
    }

    public function toArray(): array
    {
        return [
          'name' => $this->name,
          'cpfCnpj' => $this->cpfCnpj,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
          $data['name'],
          $data['cpf']
        );
    }
}
