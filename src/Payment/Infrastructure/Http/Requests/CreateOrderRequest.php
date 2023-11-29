<?php

namespace Payment\Payment\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Domain\Enum\PaymentEnum;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'billingType' => ['required', Rule::in(PaymentEnum::values())],
            'customer' => ['required', 'string'],
            'value' => ['required' , 'numeric'],
            'dueDate' => ['required', 'string'],
            'creditCard' => ['nullable', 'array'],
            'creditCardHolderInfo' => ['nullable', 'array'],
        ];
    }

    public function toDTO(): InputCreateOrder
    {
        return InputCreateOrder::fromArray($this->validated());
    }
}
