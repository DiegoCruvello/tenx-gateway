<?php

namespace Payment\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Payment\Payment\Application\Service\PaymentService;
use Payment\Payment\Domain\Exception\PaymentDomainException;
use Payment\Payment\Infrastructure\Http\Requests\ConfirmReceivedBoletoRequest;
use Payment\Payment\Infrastructure\Http\Requests\CreateOrderRequest;
use Payment\Payment\Infrastructure\Resources\PaymentResource;
use Throwable;

class PaymentController extends Controller
{
    public function __construct(
        public readonly PaymentService $service,
    ) {
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderBoleto(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderBoleto($request->toDTO());
            return PaymentResource::make($resp);
        } catch (Throwable $e) {
            throw new PaymentDomainException($e->getMessage());
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderPix(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderPix($request->toDTO());
            return PaymentResource::make($resp);
        } catch (Throwable $e) {
            throw new PaymentDomainException($e->getMessage());
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderCreditCard(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderCreditCard($request->toDTO());
            return PaymentResource::make($resp);
        } catch (Throwable $e) {
            throw new PaymentDomainException($e->getMessage());
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function confirmReceived(ConfirmReceivedBoletoRequest $request, string $id): JsonResponse
    {
        try {
            $resp = $this->service->confirmReceived($request->toDTO(), $id);
            return PaymentResource::make($resp);
        } catch (Throwable $e) {
            throw new PaymentDomainException($e->getMessage());
        }
    }
}
