<?php

namespace Payment\Payment\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Payment\Payment\Application\Service\PaymentService;
use Payment\Payment\Domain\Exception\CustomerException;
use Payment\Payment\Domain\Exception\PaymentDomainException;
use Payment\Payment\Domain\Exception\ReceivedException;
use Payment\Payment\Domain\Exception\ReceivedNotFound;
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

    public function createOrderBoleto(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderBoleto($request->toDTO());
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createOrderPix(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderPix($request->toDTO());
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createOrderCreditCard(CreateOrderRequest $request): JsonResponse
    {
        try {
            $resp = $this->service->createOrderCreditCard($request->toDTO());
            return PaymentResource::make($resp);
        } catch (CustomerException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (PaymentDomainException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function confirmReceived(ConfirmReceivedBoletoRequest $request, string $id): JsonResponse
    {
        try {
            $resp = $this->service->confirmReceived($request->toDTO(), $id);
            return PaymentResource::make($resp);
        } catch (ReceivedException $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (ReceivedNotFound $e) {
            return PaymentResource::exception($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
