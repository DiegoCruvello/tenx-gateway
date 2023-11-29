<?php

namespace Tests\Feature;

use Mockery;
use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Application\Service\ClientService;
use Payment\Payment\Domain\Entity\Client;
use Payment\Payment\Domain\Exception\ClientDomainException;
use Payment\Payment\Domain\Exception\CpfException;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    public function testShouldCreateClient(): void
    {
        $requestData = [
            'name' => 'Teste',
            'cpf' => '58176776017'
        ];

        $this->instance(ClientService::class, Mockery::mock(ClientService::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('create')
                ->with(Mockery::on(function ($inputCreateClient) use ($requestData) {
                    return $inputCreateClient instanceof InputCreateClient &&
                        $inputCreateClient->name == $requestData['name'] &&
                        $inputCreateClient->cpfCnpj == $requestData['cpf'];
                }))
                ->once()
                ->andReturn(new Client('1', 'teste', '58176776017'));
        }));

        $response = $this->postJson(route('client.store'), $requestData);

        $response->assertStatus(200);
    }

    public function testShouldShowClient(): void
    {
        $cpf = '58176776017';

        $this->instance(ClientService::class, Mockery::mock(ClientService::class, function ($mock) use ($cpf) {
            $mock->shouldReceive('getClientByCpf')
                ->with($cpf)
                ->once()
                ->andReturn(
                    new Client('1', 'teste', $cpf)
                );
        }));

        $response = $this->getJson(route('client.show', ['cpf' => $cpf]));

        $response->assertStatus(200);
    }

    public function testShouldClientDomainException(): void
    {
        $requestData = [
            'name' => 'Teste',
            'cpf' => '00000000000'
        ];

        $this->instance(ClientService::class, Mockery::mock(ClientService::class, function ($mock) use ($requestData) {
            $mock->shouldReceive('create')
                ->with(Mockery::on(function ($inputCreateClient) use ($requestData) {
                    return $inputCreateClient instanceof InputCreateClient &&
                        $inputCreateClient->name == $requestData['name'] &&
                        $inputCreateClient->cpfCnpj == $requestData['cpf'];
                }))
                ->once()
                ->andThrow(new ClientDomainException('Error inesperado', 500));
        }));

        $response = $this->postJson(route('client.store'), $requestData);

        $response->assertStatus(500);
    }

    public function testShouldThrowCpfException(): void
    {
        $cpf = '1111111111';

        $response = $this->getJson(route('client.show', ['cpf' => $cpf]));

        $response->assertStatus(400);
    }

    public function testShouldThrowClientNotFound(): void
    {
        $cpf = '74420737000';

        $response = $this->getJson(route('client.show', ['cpf' => $cpf]));

        $response->assertStatus(404);
    }
}
