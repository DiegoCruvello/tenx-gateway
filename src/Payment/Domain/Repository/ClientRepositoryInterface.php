<?php

namespace Payment\Payment\Domain\Repository;

use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function create(InputCreateClient $dto): Client;
    public function getClientByCpf(string $cpf): Client;
}
