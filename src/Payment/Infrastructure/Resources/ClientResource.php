<?php

namespace Payment\Payment\Infrastructure\Resources;

use App\Http\Resources\Resource;
use Payment\Payment\Domain\Entity\Client;

class ClientResource extends Resource
{
    public static function toArray(Client $entity): array
    {
        return $entity->toArray();
    }
}
