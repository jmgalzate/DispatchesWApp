<?php

namespace App\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ContapymeController extends AbstractController
{
    private array $arrParams;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface     $logger
    )
    {
        $this->arrParams = ['', '', $_ENV['API_IAPP'], (string)random_int(0, 9)];
    }

    #[Route('/contapyme', name: 'api_contapyme')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'method' => 'Main',
            'path' => 'src/Controller/ContapymeController.php',
            'server' => $_ENV['API_SERVER_HOST']
        ]);
    }

    #[Route('/contapyme/getauth', name: 'getauth')]
    public function getAuth(): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/';
        $this->arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => $_ENV['API_PASSWORD'],
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];

        return $this->sendRequest($this->arrParams, $endpoint);
    }

    #[Route('/contapyme/logout/{keyagent}', name: 'logout')]
    public function logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->arrParams[0] = '{}';
        $this->arrParams[1] = $keyagent;

        return $this->sendRequest($this->arrParams, $endpoint);
    }

    #[Route('/contapyme/{action}/{keyagent}/{order}', name: 'action')]
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

        return $this->sendRequest($this->arrParams, $endpoint);
    }

    // TODO implement method get products

    private function sendRequest(array $params, string $endpoint): JsonResponse
    {
        try {
            $response = $this->client->request('POST', $endpoint, [
                'json' => ['_parameters' => $params]
            ]);

            $responseData = $response->toArray();

            $this->logger->info('API request successful', [
                'endpoint' => $endpoint,
                'params' => $params,
                'responseData' => $responseData
            ]);

            return new JsonResponse([
                'path' => $endpoint,
                'parameters' => $params,
                'response' => $responseData
            ]);
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            $this->logger->error('API request failed', [
                'endpoint' => $endpoint,
                'params' => $params,
                'error' => $errorMessage
            ]);

            return new JsonResponse([
                'error' => $errorMessage
            ]);
        }
    }
}