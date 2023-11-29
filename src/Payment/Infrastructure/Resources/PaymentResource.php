<?php

namespace Payment\Payment\Infrastructure\Resources;

use App\Http\Resources\Resource;
use Illuminate\Http\JsonResponse;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;

class PaymentResource extends Resource
{
    public static function toArray(CreditCard | Boleto | Pix $entity): array
    {
        return $entity->toArray();
    }

    public static function exception(string $message, int $code): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
