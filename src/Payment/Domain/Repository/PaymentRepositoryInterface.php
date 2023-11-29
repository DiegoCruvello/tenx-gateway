<?php

namespace Payment\Payment\Domain\Repository;

use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;

interface PaymentRepositoryInterface
{
    public function createOrderCreditCard(InputCreateOrder $dto): CreditCard;
    public function createOrderBoleto(InputCreateOrder $dto): Boleto;
    public function createOrderPix(InputCreateOrder $dto): Pix;
    public function confirmReceivedBoleto(InputConfirmReceived $dto, string $id): Boleto|Pix;
}

