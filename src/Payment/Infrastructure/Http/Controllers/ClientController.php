<?php

namespace Payment\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Payment\Payment\Application\Service\ClientService;
use Payment\Payment\Domain\ValueObject\CPF;
use Payment\Payment\Infrastructure\Http\Requests\CreateClient;
use Payment\Payment\Infrastructure\Resources\ClientResource;

class ClientController extends Controller
{
    public function __construct(
        public readonly ClientService $service,
    ) {
    }

    public function store(CreateClient $request): JsonResponse
    {
        $resp = $this->service->create($request->toDTO());
        return ClientResource::make($resp);
    }

    public function show(string $cpf): JsonResponse
    {
        $cpfValid = new CPF($cpf);
        $resp = $this->service->getClientByCpf((string)$cpfValid);
        return ClientResource::make($resp);
    }
}
