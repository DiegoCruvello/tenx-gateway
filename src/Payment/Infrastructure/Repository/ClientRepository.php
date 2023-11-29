<?php

namespace Payment\Payment\Infrastructure\Repository;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Payment\Payment\Application\DTO\InputCreateClient;
use Payment\Payment\Domain\Entity\Client;
use Payment\Payment\Domain\Exception\ClientDomainException;
use Payment\Payment\Domain\Exception\ClientNotFound;
use Payment\Payment\Domain\Exception\CpfException;
use Payment\Payment\Domain\Repository\ClientRepositoryInterface;
use Payment\Payment\Infrastructure\Client\Asaas;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(
        public Asaas $asaas
    ) {
    }

    /**
     * @throws ClientDomainException
     */
    public function create(InputCreateClient $dto): Client
    {
        if($this->clientExist($dto->cpfCnpj)){
            throw new CpfException('O CPF já existe.', Response::HTTP_CONFLICT);
        }

        try {
            $resp = $this->asaas->getAsaasClient()->post('v3/customers',
                [
                    'json' => [
                        $dto->toArray()
                    ],
                ]
            );
            $data = json_decode($resp->getBody()->getContents(), true);
            return Client::fromArray($data);
        } catch (GuzzleException $e) {
            if($e->getCode() === Response::HTTP_BAD_REQUEST){
                throw new CpfException('O CPF informado é inválido.', Response::HTTP_BAD_REQUEST);
            }
            throw new ClientDomainException('Error inesperado', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws ClientDomainException
     * @throws Exception
     */
    public function getClientByCpf(string $cpf): Client
    {
        try {
            $resp = $this->asaas->getAsaasClient()->get("v3/customers?cpfCnpj=$cpf");
            $contents = json_decode($resp->getBody()->getContents(), true);
            if($contents['data'] === []){
                throw new ClientNotFound('Usuário não encontrado.', Response::HTTP_NOT_FOUND);
            }
            return Client::fromArray($contents['data'][0]);
        } catch (GuzzleException $e) {
            throw new ClientDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws ClientDomainException
     */
    private function clientExist(string $cpf): bool
    {
        try {
            $resp = $this->asaas->getAsaasClient()->get("v3/customers?cpfCnpj=$cpf");
            $contents = json_decode($resp->getBody()->getContents(), true);
            if($contents['data'] != []){
                return true;
            }
            return false;
        } catch (GuzzleException $e) {
            throw new ClientDomainException($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
