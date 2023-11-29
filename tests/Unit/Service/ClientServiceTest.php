<?php

namespace Tests\Unit\Service;

use Mockery;
use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Application\Service\ClientService;
use Payment\Payment\Domain\Entity\Client;
use Payment\Payment\Domain\Repository\ClientRepositoryInterface;
use Tests\TestCase;

class ClientServiceTest extends TestCase
{
    private ClientRepositoryInterface $repository;
    private ClientService $service;

    public function setUp(): void
    {
        $this->repository = Mockery::mock(ClientRepositoryInterface::class);
        $this->service = new ClientService($this->repository);
    }

    public function testShouldCreateClientWithSuccess(): void
    {
        $dto = new InputCreateClient(
            'teste',
            '000000000000'
        );
        $this->repository
            ->expects('create')
            ->once()
            ->andReturn(
                new Client('1', 'teste', '000000000000')
            );
        $response = $this->service->create($dto);
        $this->assertInstanceOf(Client::class, $response);
    }
}
