<?php

namespace Payment\Payment\Application\Service;

use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Domain\Entity\Client;
use Payment\Payment\Infrastructure\Repository\ClientRepository;

readonly class ClientService
{
    public function __construct(
        public ClientRepository $repository
    ) {
    }

    public function create(InputCreateClient $dto): Client
    {
        return $this->repository->create($dto);
    }

    public function getClientByCpf(string $cpf): Client
    {
        return $this->repository->getClientByCpf($cpf);
    }
}
