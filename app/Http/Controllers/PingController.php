<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PingController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'code' => Response::HTTP_OK,
            'status' => 'healthy',
            'uri' => $request->getUri(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
