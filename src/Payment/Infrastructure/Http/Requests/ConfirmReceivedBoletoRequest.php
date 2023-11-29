<?php

namespace Payment\Payment\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Payment\Application\DTO\InputConfirmReceived;

class ConfirmReceivedBoletoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'paymentDate' => ['required', 'string'],
            'value' => ['nullable', 'numeric'],
        ];
    }

    public function toDTO(): InputConfirmReceived
    {
        return InputConfirmReceived::fromArray($this->validated());
    }
}
