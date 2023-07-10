<?php

namespace App\Controller;

use App\Service\ContapymeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ContapymeController extends AbstractController
{
    private array $arrParams;

    /**
     * @throws \Exception
     */
    public function __construct(
        private readonly ContapymeService $apiService
    )
    {
        $this->arrParams = ['', '', $_ENV['API_IAPP'], (string)random_int(0, 9)];
    }

    public function getAuth(): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/';
        $this->arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];

        $responseData = $this->apiService->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        try {
            setcookie('keyagent', $responseDataArray['body']['keyagente'], time() + 3600, '/');
            return new JsonResponse([
                'Confirmation' => 'Cookie set'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'Error' => $e->getMessage()
            ]);
        }
    }

    public function logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->arrParams[0] = '{}';
        $this->arrParams[1] = $keyagent;

        $responseData = $this->apiService->sendRequest($this->arrParams, $endpoint);
        $responseDataArray = json_decode($responseData->getContent(), true);

        try {
            setcookie('keyagent', '', time() + 3600, '/');
            return new JsonResponse([
                'Session closed' => $responseDataArray['body']['cerro'],
                'Confirmation' => 'Cookie unset'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'Error' => $e->getMessage()
            ]);
        }
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
        $responseData = $this->apiService->sendRequest($this->arrParams, $endpoint);
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

    public function getProducts(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatElemInv/"GetListaElemInv"/';
        $this->arrParams[0] = [
            'datospagina' => [
                'cantidadregistros' => "5",
                'pagina' => ''
            ],
            'camposderetorno' => [
                'irecurso', 'nrecurso', 'clase2'
            ]
        ];

        $this->arrParams[1] = $keyagent;
        $responseData = $this->apiService->sendRequest($this->arrParams, $endpoint);
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