<?php

namespace Payment\Payment\Infrastructure\Repository;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Domain\Exception\CustomerException;
use Payment\Payment\Domain\Exception\PaymentDomainException;
use Payment\Payment\Domain\Exception\ReceivedException;
use Payment\Payment\Domain\Exception\ReceivedNotFound;
use Payment\Payment\Domain\Repository\PaymentRepositoryInterface;
use Payment\Payment\Infrastructure\Client\Asaas;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(
        public Asaas $asaas
    ) {
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderCreditCard(InputCreateOrder $dto): CreditCard
    {
        try {
            $resp = $this->asaas->getAsaasClient()->post('v3/payments',
                [
                    'json' => [
                        $dto->toArray()
                    ],
                ]
            );
            $data = json_decode($resp->getBody()->getContents(), true);
            return CreditCard::fromArray($data);
        } catch (GuzzleException $e) {
            if($e->getCode() === Response::HTTP_BAD_REQUEST){
                throw new CustomerException('O customer informado não existe.', Response::HTTP_BAD_REQUEST);
            }
            throw new PaymentDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderBoleto(InputCreateOrder $dto): Boleto
    {
        try {
            $resp = $this->asaas->getAsaasClient()->post('v3/payments',
                [
                    'json' => [
                        $dto->toArray()
                    ],
                ]
            );
            $data = json_decode($resp->getBody()->getContents(), true);
            return Boleto::fromArray($data);
        } catch (GuzzleException $e) {
            if($e->getCode() === Response::HTTP_BAD_REQUEST){
                throw new CustomerException('O customer informado não existe.', Response::HTTP_BAD_REQUEST);
            }
            throw new PaymentDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function createOrderPix(InputCreateOrder $dto): Pix
    {
        try {
            $resp = $this->asaas->getAsaasClient()->post('v3/payments',
                [
                    'json' => [
                        $dto->toArray()
                    ],
                ]
            );
            $data = json_decode($resp->getBody()->getContents(), true);
            return Pix::fromArray($data);
        } catch (GuzzleException $e) {
            if($e->getCode() === Response::HTTP_BAD_REQUEST){
                throw new CustomerException('O customer informado não existe.', Response::HTTP_BAD_REQUEST);
            }
            throw new PaymentDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws PaymentDomainException
     */
    public function confirmReceivedBoleto(InputConfirmReceived $dto, string $id): Boleto|Pix
    {
        try {
            $resp = $this->asaas->getAsaasClient()->post("v3/payments/$id/receiveInCash",
                [
                    'json' => [
                        $dto->toArray()
                    ],
                ]
            );
            $data = json_decode($resp->getBody()->getContents(), true);
            return $data['bankSlipUrl'] === null ? Pix::fromArray($data) : Boleto::fromArray($data);
        } catch (GuzzleException $e) {
            if($e->getCode() === Response::HTTP_BAD_REQUEST){
                throw new ReceivedException('Essa fatura já está paga.', Response::HTTP_CONFLICT);
            }
            if($e->getCode() === Response::HTTP_NOT_FOUND){
                throw new ReceivedNotFound('A fatura não foi encontrada', Response::HTTP_NOT_FOUND);
            }
            throw new PaymentDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
