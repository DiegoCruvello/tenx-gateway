<?php

namespace Payment\Payment\Infrastructure\Resources;

use App\Http\Resources\Resource;
use Illuminate\Http\JsonResponse;
use Payment\Payment\Domain\Entity\Client;

class ClientResource extends Resource
{
    public static function toArray(Client $entity): array
    {
        return $entity->toArray();
    }

    public static function exception(string $message, int $code): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }
}
