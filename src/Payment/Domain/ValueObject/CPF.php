<?php

namespace Payment\Payment\Domain\ValueObject;

use App\Helpers\ValidateCPF;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Payment\Payment\Domain\Exception\CpfException;

final class CPF
{
    /**
     * @throws CpfException
     */
    public function __construct(
        private string $cpf
    ) {
        $this->validate();
    }

    public function __toString(): string
    {
        return preg_replace('/[^\d]/', '', $this->cpf);
    }

    /**
     * @throws CpfException
     */
    public function validate(): void
    {
        if (! ValidateCPF::validate($this->cpf)) {
            throw new CpfException(sprintf('CPF %s is invalid.', $this->cpf), Response::HTTP_BAD_REQUEST);
        }
    }
}
