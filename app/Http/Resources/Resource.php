<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;

abstract class Resource
{
    public static function make(mixed $entity, array $att = []): JsonResponse
    {
        return response()->json(array_merge(static::toArray($entity), $att));
    }
}
