<?php

// do you see something here that could be managed by a service instead of a controller?

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

    #[Route('/contapyme/getauth', name: 'getauth')]
    public function getAuth(): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/';
        $this->arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => $_ENV['API_PASSWORD'],
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];

        return $this->apiService->sendRequest($this->arrParams, $endpoint);
    }

    #[Route('/contapyme/logout/{keyagent}', name: 'logout')]
    public function logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->arrParams[0] = '{}';
        $this->arrParams[1] = $keyagent;

        return $this->apiService->sendRequest($this->arrParams, $endpoint);
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

        return $this->apiService->sendRequest($this->arrParams, $endpoint);
    }

    // TODO implement method get products

}