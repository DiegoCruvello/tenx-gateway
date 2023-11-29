<?php

namespace Payment\Payment\Infrastructure\Http\Rules;

use App\Helpers\ValidateCPF;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfFilterRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        ValidateCPF::validate((string) $value);
    }
}
