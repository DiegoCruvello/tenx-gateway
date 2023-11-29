<?php

namespace Tests\Unit\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Domain\Entity\Client as ClientEntity;
use Payment\Payment\Domain\Exception\CpfException;
use Payment\Payment\Infrastructure\Client\Asaas;
use Payment\Payment\Infrastructure\Repository\ClientRepository;
use Tests\TestCase;

class ClientRepositoryTest extends TestCase
{
    private ClientRepository $repository;
    private Asaas $client;

    public function setUp(): void
    {
        $this->client = Mockery::mock(Asaas::class);
        $this->repository = new ClientRepository($this->client);
    }

    public function testShouldCreateNewClient(): void
    {
        $dto = InputCreateClient::fromArray([
            'name' => 'teste',
            'cpf' => '00000000000',
        ]);

        $guzzleClientMock = Mockery::mock(Client::class);
        $this->client->allows('getAsaasClient')
            ->andReturns($guzzleClientMock);

        $responseMock = new Response(200, [], json_encode(['data' => []]));
        $guzzleClientMock->allows('get')
            ->with('v3/customers?cpfCnpj=00000000000')
            ->andReturns($responseMock);

        // Mock para a chamada 'post'
        $postResponseMock = new Response(200, [], json_encode([
            'id' => '1',
            'name' => 'teste',
            'cpfCnpj' => '00000000000',
        ]));
        $guzzleClientMock->allows('post')
            ->with('v3/customers', [
                'json' => [
                    $dto->toArray(),
                ],
            ])
            ->andReturns($postResponseMock);

        $resp = $this->repository->create($dto);

        $this->assertInstanceOf(ClientEntity::class, $resp);
    }

    public function testShouldThrowCpfException(): void
    {
        $this->expectException(CpfException::class);
        $dto = InputCreateClient::fromArray([
            'name' => 'teste',
            'cpf' => '00000000000',
        ]);

        $guzzleClientMock = Mockery::mock(Client::class);
        $this->client->allows('getAsaasClient')
            ->andReturns($guzzleClientMock);

        $responseMock = new Response(200, [], json_encode(['data' => [false]]));
        $guzzleClientMock->allows('get')
            ->with('v3/customers?cpfCnpj=00000000000')
            ->andReturns($responseMock);

        // Mock para a chamada 'post'
        $postResponseMock = new Response(200, [], json_encode([
            'id' => '1',
            'name' => 'teste',
            'cpfCnpj' => '00000000000',
        ]));
        $guzzleClientMock->allows('post')
            ->with('v3/customers', [
                'json' => [
                    $dto->toArray(),
                ],
            ])
            ->andReturns($postResponseMock);

        $this->repository->create($dto);
    }
}
