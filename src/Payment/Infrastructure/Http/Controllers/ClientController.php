<?php

namespace Payment\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Payment\Payment\Application\Service\ClientService;
use Payment\Payment\Domain\Exception\ClientDomainException;
use Payment\Payment\Domain\Exception\ClientNotFound;
use Payment\Payment\Domain\Exception\CpfException;
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
        try {
            $resp = $this->service->create($request->toDTO());
            return ClientResource::make($resp);
        } catch (ClientDomainException $e){
            return ClientResource::exception($e->getMessage(), $e->getCode());
        }
    }

    public function show(string $cpf): JsonResponse
    {
        try {
            $cpfValid = new CPF($cpf);
            $resp = $this->service->getClientByCpf((string)$cpfValid);
            return ClientResource::make($resp);
        } catch (CpfException $e){
            return ClientResource::exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (ClientNotFound $e){
            return ClientResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
