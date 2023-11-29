<?php

namespace Tests\Unit\Repository;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Infrastructure\Client\Asaas;
use Payment\Payment\Infrastructure\Repository\PaymentRepository;
use Tests\TestCase;

class PaymentRepositoryTest extends TestCase
{
    private PaymentRepository $repository;
    private Asaas $client;
    private MockHandler $mockHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $guzzleClient = new Client(['handler' => $handlerStack]);

        $this->client = Mockery::mock(Asaas::class);
        $this->client->shouldReceive('getAsaasClient')
            ->andReturn($guzzleClient);

        $this->repository = new PaymentRepository($this->client);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testCreateOrderCreditCardSuccessfully(): void
    {
        $dto = InputCreateOrder::fromArray([
            "customer" => "cus_000005796310",
            "billingType" => "CREDIT_CARD",
            "value" => 100.00,
            "dueDate" => "2023-12-21",
            "creditCard" => [
                "holderName" => "marcelo h almeida",
                "number" => "5162306219378829",
                "expiryMonth" => "05",
                "expiryYear" => "2024",
                "ccv" => "318"
            ],
            "creditCardHolderInfo" => [
                "name" => "Marcelo Henrique Almeida",
                "email" => "marcelo.almeida@gmail.com",
                "cpfCnpj" => "24971563792",
                "postalCode" => "89223-005",
                "addressNumber" => "277",
                "addressComplement" => null,
                "phone" => "4738010919",
                "mobilePhone" => "47998781877"
            ]
        ]);
        $expectedCreditCard = CreditCard::fromArray([
            "id" => "pay_r74ngjk06hzxtppv",
            "customer" => "cus_000005796310",
            "status" => "PENDING",
            "transactionReceiptUrl" => "https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv",
            "invoiceUrl" => "https://sandbox.asaas.com/i/r74ngjk06hzxtppv"
        ]);

        $this->mockHandler->append(new Response(200, [], json_encode($expectedCreditCard->toArray())));

        $creditCard = $this->repository->createOrderCreditCard($dto);

        $this->assertInstanceOf(CreditCard::class, $creditCard);
    }

    public function testCreateOrderBoletoSuccessfully(): void
    {
        $dto = InputCreateOrder::fromArray([
            "customer" => "cus_000005796310",
            "billingType" => "BOLETO",
            "value" => 100.00,
            "dueDate" => "2023-12-21",
        ]);
        $expectedBoleto = Boleto::fromArray([
            "id" => "pay_r74ngjk06hzxtppv",
            "customer" => "cus_000005796310",
            "status" => "PENDING",
            "bankSlipUrl" => "https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv",
            "invoiceUrl" => "https://sandbox.asaas.com/i/r74ngjk06hzxtppv"
        ]);

        $this->mockHandler->append(new Response(200, [], json_encode($expectedBoleto->toArray())));

        $boleto = $this->repository->createOrderBoleto($dto);

        $this->assertInstanceOf(Boleto::class, $boleto);
    }

    public function testCreateOrderPixSuccessfully(): void
    {
        $dto = InputCreateOrder::fromArray([
            "customer" => "cus_000005796310",
            "billingType" => "PIX",
            "value" => 100.00,
            "dueDate" => "2023-12-21",
        ]);
        $expectedPix = Pix::fromArray([
            "id" => "pay_r74ngjk06hzxtppv",
            "customer" => "cus_000005796310",
            "status" => "PENDING",
            "invoiceUrl" => "https://sandbox.asaas.com/i/r74ngjk06hzxtppv"
        ]);

        $this->mockHandler->append(new Response(200, [], json_encode($expectedPix->toArray())));

        $pix = $this->repository->createOrderPix($dto);

        $this->assertInstanceOf(Pix::class, $pix);
    }

    public function testConfirmReceivedBoletoSuccessfully(): void
    {
        $dto = InputConfirmReceived::fromArray([
            'paymentDate' => '2023-11-29',
            'value' => 100,
        ]);
        $id = 'pay_r74ngjk06hzxtppv';
        $expectedResponse = Boleto::fromArray([
            "id" => "pay_r74ngjk06hzxtppv",
            "customer" => "cus_000005796310",
            "status" => "PENDING",
            "bankSlipUrl" => "https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv",
            "invoiceUrl" => "https://sandbox.asaas.com/i/r74ngjk06hzxtppv"
        ]);

        $this->mockHandler->append(new Response(200, [], json_encode($expectedResponse->toArray())));

        $response = $this->repository->confirmReceivedBoleto($dto, $id);

        $this->assertInstanceOf(Boleto::class, $response);
    }
}
