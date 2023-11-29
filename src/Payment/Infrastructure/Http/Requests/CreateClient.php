<?php

namespace Payment\Payment\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Infrastructure\Http\Rules\CpfFilterRule;

class CreateClient extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'cpf' => ['required', new CpfFilterRule()],
        ];
    }

    public function toDTO(): InputCreateClient
    {
        return InputCreateClient::fromArray($this->validated());
    }
}
