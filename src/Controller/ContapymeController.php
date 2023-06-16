<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;


class ContapymeController extends AbstractController
{
    private array $_arrParams;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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

    #[Route('/contapyme/getauth', name: 'getAuth')]
    public function getAuth(): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/';
        $this->_arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => $_ENV['API_PASSWORD'],
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];
        $this->_arrParams[1] = '';
        $this->_arrParams[2] = $_ENV['API_IAPP'];
        $this->_arrParams[3] = (string)random_int(0,9);


        return $this->json([
            'path' => $endpoint,
            'method' => 'getAuth',
            'parameters' => $this->_arrParams,
            'response' =>  $this->apiRequest($this->_arrParams, $endpoint)
        ]);
    }

    #[Route('/contapyme/Logout/{keyagent}', name: 'Logout')]
    public function Logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->_arrParams[0] = '{}';
        $this->_arrParams[1] = $keyagent;
        $this->_arrParams[2] = $_ENV['API_IAPP'];
        $this->_arrParams[3] = (string)random_int(0,9);

        return $this->json([
            'method' => 'Logout',
            'path' => $endpoint,
            'parameters' => $this->_arrParams,
            'response' => $this->apiRequest($this->_arrParams, $endpoint)
        ]);
    }

    #[Route('/contapyme/request/{params}/{endpoint}', name: 'apiRequest')]
    public function apiRequest(array $params, string $endpoint): JsonResponse
    {
       return new JsonResponse('{"error": "No se ha podido conectar con el servidor"}');
    }
}
