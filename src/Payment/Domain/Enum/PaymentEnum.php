<?php

namespace Payment\Payment\Domain\Enum;

enum PaymentEnum: string
{
    case PAYMENT_BOLETO = 'BOLETO';
    case PAYMENT_PIX = 'PIX';
    case PAYMENT_CREDIT_CARD = 'CREDIT_CARD';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
