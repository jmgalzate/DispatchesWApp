<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContapymeService
{
    private array $arrParams;

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface     $logger
    )
    {
        $this->arrParams = ['', '', $_ENV['API_IAPP'], (string)random_int(0, 9)];
    }

    public function sendRequest(array $params, string $endpoint): JsonResponse
    {
        try {
            $response = $this->client->request('POST', $endpoint, [
                'json' => ['_parameters' => $params]
            ]);

            $responseData = $response->toArray();
            $header = $responseData["result"][0]["encabezado"];
            $statusCode = $response->getStatusCode();
            $body = $responseData["result"][0]["respuesta"]["datos"];

            $this->logger->info('API request successful', [
                'endpoint' => $endpoint,
                'statusCode' => $statusCode,
                'params' => $params,
                'header' => $header,
                'body' => $body,
            ]);

            return new JsonResponse([
                'body' => $body
            ]);

        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            $this->logger->error('API request failed', [
                'endpoint' => $endpoint,
                'error' => $errorMessage
            ]);

            return new JsonResponse([
                'error' => $errorMessage
            ]);
        }
    }

    public function getAuth(): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/';
        $this->arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];

        $responseData = $this->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        return new JsonResponse([
            'keyagent' => $responseDataArray['body']['keyagente']
        ]);
    }

    public function logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->arrParams[0] = '{}';
        $this->arrParams[1] = $keyagent;

        $responseData = $this->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        return new JsonResponse([
            'Session closed' => $responseDataArray['body']['cerro']
        ]);
    }

    public function action(string $action, string $keyagent, string $order, array $newOrder = []): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatOperaciones/"DoExecuteOprAction"/';

        if ($action == 'SAVE') {
            $this->arrParams[0] = [
                'accion' => $action,
                'operaciones' => [
                    [
                        'inumoper' => $order,
                        'itdoper' => $_ENV['API_ITDOPER']
                    ]
                ],
                'oprdata' => $newOrder
            ];
        } else {
            $this->arrParams[0] = [
                'accion' => $action,
                'operaciones' => [
                    [
                        'inumoper' => $order,
                        'itdoper' => $_ENV['API_ITDOPER']
                    ]
                ]
            ];
        }

        $this->arrParams[1] = $keyagent;
        $responseData = $this->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        try {
            return new JsonResponse([
                'body' => $responseDataArray['body']
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'Error' => $e->getMessage()
            ]);
        }
    }

    public function getProducts(string $keyagent, string $cant): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatElemInv/"GetListaElemInv"/';
        $this->arrParams[0] = [
            'datospagina' => [
                'cantidadregistros' => $cant
            ],
            'camposderetorno' => [
                'irecurso', 'nrecurso', 'clase2'
            ]
        ];

        $this->arrParams[1] = $keyagent;
        $responseData = $this->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        try {
            return new JsonResponse([
                'body' => $responseDataArray['body']
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'Error' => $e->getMessage()
            ]);
        }
    }
}