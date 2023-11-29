<?php

namespace Tests\Unit\Service;

use Mockery;
use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Application\Service\PaymentService;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Domain\Repository\PaymentRepositoryInterface;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentRepositoryInterface $repository;
    private PaymentService $service;

    public function setUp(): void
    {
        $this->repository = Mockery::mock(PaymentRepositoryInterface::class);
        $this->service = new PaymentService($this->repository);
    }

    public function testShouldCreateCreditCardOrderSuccessfully(): void
    {
        $inputCreateOrder = InputCreateOrder::fromArray([
            'customer' => 'cus_000005796310',
            'billingType' => 'CREDIT_CARD',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
            'creditCard' => [
                'holderName' => 'marcelo h almeida',
                'number' => '5162306219378829',
                'expiryMonth' => '05',
                'expiryYear' => '2024',
                'ccv' => '318',
            ],
            'creditCardHolderInfo' => [
                'name' => 'Marcelo Henrique Almeida',
                'email' => 'marcelo.almeida@gmail.com',
                'cpfCnpj' => '24971563792',
                'postalCode' => '89223-005',
                'addressNumber' => '277',
                'addressComplement' => null,
                'phone' => '4738010919',
                'mobilePhone' => '47998781877',
            ],
        ]);
        $expectedCreditCard = CreditCard::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'transactionReceiptUrl' => 'https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv',
        ]);

        $this->repository->expects('createOrderCreditCard')
            ->with($inputCreateOrder)
            ->andReturns($expectedCreditCard);

        $creditCard = $this->service->createOrderCreditCard($inputCreateOrder);

        $this->assertInstanceOf(CreditCard::class, $creditCard);
    }

    public function testShouldCreateBoletoOrderSuccessfully(): void
    {
        $inputCreateOrder = InputCreateOrder::fromArray([
            'customer' => 'cus_000005796310',
            'billingType' => 'BOLETO',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
        ]);
        $expectedBoleto = Boleto::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'bankSlipUrl' => 'https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv',
        ]);

        $this->repository->expects('createOrderBoleto')
            ->with($inputCreateOrder)
            ->andReturns($expectedBoleto);

        $creditCard = $this->service->createOrderBoleto($inputCreateOrder);

        $this->assertInstanceOf(Boleto::class, $creditCard);
    }

    public function testShouldCreatePixOrderSuccessfully(): void
    {
        $inputCreateOrder = InputCreateOrder::fromArray([
            'customer' => 'cus_000005796310',
            'billingType' => 'PIX',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
        ]);
        $expectedBoleto = Pix::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv',
        ]);

        $this->repository->expects('createOrderPix')
            ->with($inputCreateOrder)
            ->andReturns($expectedBoleto);

        $creditCard = $this->service->createOrderPix($inputCreateOrder);

        $this->assertInstanceOf(Pix::class, $creditCard);
    }

    public function testShouldConfirmReceivedPaymentSuccessfully(): void
    {
        $inputConfirmReceived = InputConfirmReceived::fromArray([
            'paymentDate' => '2023-11-29',
            'value' => 100,
        ]);
        $paymentId = 'pay_r74ngjk06hzxtppv';
        $expectedPayment = Boleto::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'bankSlipUrl' => 'https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv',
        ]);

        $this->repository->expects('confirmReceivedBoleto')
            ->with($inputConfirmReceived, $paymentId)
            ->andReturns($expectedPayment);

        $payment = $this->service->confirmReceived($inputConfirmReceived, $paymentId);

        $this->assertInstanceOf(Boleto::class, $payment);
    }
}
