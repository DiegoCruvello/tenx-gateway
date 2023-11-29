<?php

namespace Payment\Payment\Application\Service;

use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Domain\Exception\PaymentDomainException;
use Payment\Payment\Domain\Repository\PaymentRepositoryInterface;

readonly class PaymentService
{
    public function __construct(
        public PaymentRepositoryInterface $repository,
    ) {
    }

    public function createOrderCreditCard(InputCreateOrder $dto): CreditCard
    {
        return $this->repository->createOrderCreditCard($dto);
    }

    public function createOrderBoleto(InputCreateOrder $dto): Boleto
    {
        return $this->repository->createOrderBoleto($dto);
    }

    public function createOrderPix(InputCreateOrder $dto): Pix
    {
        return $this->repository->createOrderPix($dto);
    }

    public function confirmReceived(InputConfirmReceived $dto, string $id): Pix|Boleto
    {
        return $this->repository->confirmReceivedBoleto($dto, $id);
    }
}
