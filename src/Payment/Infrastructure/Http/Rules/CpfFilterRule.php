<?php

namespace Payment\Payment\Infrastructure\Http\Rules;

use App\Helpers\ValidateCPF;
use Illuminate\Contracts\Validation\Rule;

class CpfFilterRule implements Rule
{
    private string $attribute;

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        if (is_array($value)) {
            return collect($value)
                ->every(fn ($entry) => ValidateCPF::validate((string) $entry));
        }

        return ValidateCPF::validate((string) $value);
    }

    public function message(): string
    {
        return sprintf(
            "O campo '%s' deve conter um CPF válido.",
            $this->attribute
        );
    }
}
