<?php

namespace Payment\Payment\Domain\ValueObject;

use App\Helpers\ValidateCPF;
use InvalidArgumentException;

final class CPF
{
    public function __construct(
        private string $cpf
    ) {
        $this->validate();
    }

    public function __toString(): string
    {
        return preg_replace('/[^\d]/', '', $this->cpf);
    }

    public function validate(): void
    {
        if (! ValidateCPF::validate($this->cpf)) {
            throw new InvalidArgumentException(sprintf('CPF %s is invalid.', $this->cpf));
        }
    }
}
