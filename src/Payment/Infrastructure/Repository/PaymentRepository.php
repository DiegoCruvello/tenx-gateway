<?php

namespace Payment\Payment\Infrastructure\Repository;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Domain\Exception\PaymentDomainException;
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
            throw new PaymentDomainException($e->getMessage());
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
            throw new PaymentDomainException($e->getMessage());
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
            throw new PaymentDomainException($e->getMessage());
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
                throw new PaymentDomainException('Essa fatura já está paga.');
            }
            throw new PaymentDomainException($e->getMessage());
        }
    }
}
