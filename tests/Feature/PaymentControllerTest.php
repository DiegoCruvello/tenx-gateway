<?php

namespace Tests\Feature;

use Mockery;
use Payment\Payment\Application\DTO\InputConfirmReceived;
use Payment\Payment\Application\DTO\InputCreateOrder;
use Payment\Payment\Application\Service\PaymentService;
use Payment\Payment\Domain\Entity\Boleto;
use Payment\Payment\Domain\Entity\CreditCard;
use Payment\Payment\Domain\Entity\Pix;
use Payment\Payment\Domain\Exception\CustomerException;
use Payment\Payment\Domain\Exception\PaymentDomainException;
use Payment\Payment\Domain\Exception\ReceivedException;
use Payment\Payment\Domain\Exception\ReceivedNotFound;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function testShouldCreateOrderBoletoPayment(): void
    {
        $requestData = [
            'billingType' => 'BOLETO',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $expectedResponse = Boleto::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'bankSlipUrl' => 'https://sandbox.asaas.com/b/pdf/r74ngjk06hzxtppv',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv'
        ]);

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) use ($expectedResponse, $requestData) {
            $mock->shouldReceive('createOrderBoleto')
                ->with(Mockery::on(function ($inputCreateOrder) use ($requestData) {
                    return $inputCreateOrder instanceof InputCreateOrder &&
                        $inputCreateOrder->billingType == $requestData['billingType'] &&
                        $inputCreateOrder->customer == $requestData['customer'] &&
                        $inputCreateOrder->value == $requestData['value'] &&
                        $inputCreateOrder->dueDate == $requestData['dueDate'];
                }))
                ->once()
                ->andReturn($expectedResponse);
        }));

        $response = $this->postJson(route('payment.boleto'), $requestData);

        $response->assertStatus(200);
    }

    public function testShouldCreateOrderPixPayment(): void
    {
        $requestData = [
            'billingType' => 'PIX',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $expectedResponse = Pix::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv'
        ]);

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) use ($expectedResponse, $requestData) {
            $mock->shouldReceive('createOrderPix')
                ->with(Mockery::on(function ($inputCreateOrder) use ($requestData) {
                    return $inputCreateOrder instanceof InputCreateOrder &&
                        $inputCreateOrder->billingType == $requestData['billingType'] &&
                        $inputCreateOrder->customer == $requestData['customer'] &&
                        $inputCreateOrder->value == $requestData['value'] &&
                        $inputCreateOrder->dueDate == $requestData['dueDate'];
                }))
                ->once()
                ->andReturn($expectedResponse);
        }));

        $response = $this->postJson(route('payment.pix'), $requestData);

        $response->assertStatus(200);
    }

    public function testShouldCreateOrderCreditCardPayment(): void
    {
        $requestData = [
            'customer' => 'cus_000005796310',
            'billingType' => 'CREDIT_CARD',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
            'creditCard' => [
                'holderName' => 'marcelo h almeida',
                'number' => '5162306219378829',
                'expiryMonth' => '05',
                'expiryYear' => '2024',
                'ccv' => '318'
            ],
            'creditCardHolderInfo' => [
                'name' => 'Marcelo Henrique Almeida',
                'email' => 'marcelo.almeida@gmail.com',
                'cpfCnpj' => '24971563792',
                'postalCode' => '89223-005',
                'addressNumber' => '277',
                'addressComplement' => null,
                'phone' => '4738010919',
                'mobilePhone' => '47998781877'
            ]
        ];

        $expectedResponse = CreditCard::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/5rvkm6cq98nywjx4',
            'transactionReceiptUrl' => 'https://sandbox.asaas.com/i/5rvkm6cq98nywjx4'
        ]);

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) use ($expectedResponse, $requestData) {
            $mock->shouldReceive('createOrderCreditCard')
                ->with(Mockery::on(function ($inputCreateOrder) use ($requestData) {
                    return $inputCreateOrder instanceof InputCreateOrder &&
                        $inputCreateOrder->billingType == $requestData['billingType'] &&
                        $inputCreateOrder->customer == $requestData['customer'] &&
                        $inputCreateOrder->value == $requestData['value'] &&
                        $inputCreateOrder->dueDate == $requestData['dueDate'];
                }))
                ->once()
                ->andReturn($expectedResponse);
        }));

        $response = $this->postJson(route('payment.credit_card'), $requestData);

        $response->assertStatus(200);
    }

    public function testShouldReturnReceivedConfirmPix(): void
    {
        $requestData = [
            'paymentDate' => '2030-11-29',
            'value' => 100,
        ];

        $expectedResponse = Pix::fromArray([
            'id' => 'pay_r74ngjk06hzxtppv',
            'customer' => 'cus_000005796310',
            'status' => 'PENDING',
            'invoiceUrl' => 'https://sandbox.asaas.com/i/r74ngjk06hzxtppv'
        ]);

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) use ($expectedResponse) {
            $mock->shouldReceive('confirmReceived')
                ->withArgs(function ($inputConfirmReceived, $id) {
                    return $inputConfirmReceived instanceof InputConfirmReceived && $id === 'pay_r74ngjk06hzxtppv';
                })
                ->once()
                ->andReturn($expectedResponse);
        }));

        $response = $this->postJson(route('payment.confirm.received', ['id' => 'pay_r74ngjk06hzxtppv']), $requestData);

        $response->assertStatus(200);
    }

    public function testShouldThrowBoletoCustomerException(): void
    {
        $requestData = [
            'billingType' => 'BOLETO',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderBoleto')
                ->andThrow(new CustomerException('O customer informado não existe.', 404));
        }));

        $response = $this->postJson(route('payment.boleto'), $requestData);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'O customer informado não existe.']);
    }

    public function testShouldThrowPixCustomerException(): void
    {
        $requestData = [
            'billingType' => 'PIX',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderPix')
                ->andThrow(new CustomerException('O customer informado não existe.', 404));
        }));

        $response = $this->postJson(route('payment.pix'), $requestData);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'O customer informado não existe.']);
    }

    public function testShouldThrowCreditCardCustomerException(): void
    {
        $requestData = [
            'customer' => 'cus_000005796310',
            'billingType' => 'CREDIT_CARD',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
            'creditCard' => [
                'holderName' => 'marcelo h almeida',
                'number' => '5162306219378829',
                'expiryMonth' => '05',
                'expiryYear' => '2024',
                'ccv' => '318'
            ],
            'creditCardHolderInfo' => [
                'name' => 'Marcelo Henrique Almeida',
                'email' => 'marcelo.almeida@gmail.com',
                'cpfCnpj' => '24971563792',
                'postalCode' => '89223-005',
                'addressNumber' => '277',
                'addressComplement' => null,
                'phone' => '4738010919',
                'mobilePhone' => '47998781877'
            ]
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderCreditCard')
                ->andThrow(new CustomerException('O customer informado não existe.', 404));
        }));

        $response = $this->postJson(route('payment.credit_card'), $requestData);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'O customer informado não existe.']);
    }

    public function testShouldThrowBoletoPaymentDomainException(): void
    {
        $requestData = [
            'billingType' => 'BOLETO',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderBoleto')
                ->andThrow(new PaymentDomainException('Erro inesperado', 500));
        }));

        $response = $this->postJson(route('payment.boleto'), $requestData);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Erro inesperado']);
    }

    public function testShouldThrowPixPaymentDomainException(): void
    {
        $requestData = [
            'billingType' => 'PIX',
            'customer' => 'cus_000005796310',
            'value' => 10.2,
            'dueDate' => '2023-11-30'
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderPix')
                ->andThrow(new PaymentDomainException('Erro inesperado', 500));
        }));

        $response = $this->postJson(route('payment.pix'), $requestData);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Erro inesperado']);
    }

    public function testShouldThrowCreditardPaymentDomainException(): void
    {
        $requestData = [
            'customer' => 'cus_000005796310',
            'billingType' => 'CREDIT_CARD',
            'value' => 100.00,
            'dueDate' => '2023-12-21',
            'creditCard' => [
                'holderName' => 'marcelo h almeida',
                'number' => '5162306219378829',
                'expiryMonth' => '05',
                'expiryYear' => '2024',
                'ccv' => '318'
            ],
            'creditCardHolderInfo' => [
                'name' => 'Marcelo Henrique Almeida',
                'email' => 'marcelo.almeida@gmail.com',
                'cpfCnpj' => '24971563792',
                'postalCode' => '89223-005',
                'addressNumber' => '277',
                'addressComplement' => null,
                'phone' => '4738010919',
                'mobilePhone' => '47998781877'
            ]
        ];

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('createOrderCreditCard')
                ->andThrow(new PaymentDomainException('Erro inesperado', 500));
        }));

        $response = $this->postJson(route('payment.credit_card'), $requestData);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Erro inesperado']);
    }

    public function testShouldThrowBoletoReceivedException(): void
    {
        $requestData = [
            'paymentDate' => '2023-11-30',
            'value' => 10.2,
        ];

        $paymentId = 'pay_r74ngjk06hzxtppv';

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('confirmReceived')
                ->andThrow(new ReceivedException('Essa fatura já está paga.', 400));
        }));

        $response = $this->postJson(route('payment.confirm.received', ['id' => $paymentId]), $requestData);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Essa fatura já está paga.']);
    }

    public function testShouldThrowBoletoReceivedNotFoundException(): void
    {
        $requestData = [
            'paymentDate' => '2023-11-30',
            'value' => 10.2,
        ];

        $paymentId = 'pay_r74ngjk06hzxtppv';

        $this->instance(PaymentService::class, Mockery::mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('confirmReceived')
                ->andThrow(new ReceivedNotFound('A fatura não foi encontrada', 404));
        }));

        $response = $this->postJson(route('payment.confirm.received', ['id' => $paymentId]), $requestData);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'A fatura não foi encontrada']);
    }
}
