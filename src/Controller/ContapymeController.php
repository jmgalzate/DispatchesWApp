<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;


class ContapymeController extends AbstractController
{
    private array $_arrParams;

    public function __construct(private HttpClientInterface $client, private LoggerInterface $logger)
    {

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
        $this->_arrParams[0] = [
            'email' => $_ENV['API_USERNAME'],
            'password' => $_ENV['API_PASSWORD'],
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ];
        $this->_arrParams[1] = '';
        $this->_arrParams[2] = $_ENV['API_IAPP'];
        $this->_arrParams[3] = (string)random_int(0,9);

        return new JsonResponse(
            [
                'path' => $endpoint,
                'method' => 'getAuth',
                'parameters' => $this->_arrParams,
                'response' =>  $this->request($this->_arrParams, $endpoint)
            ]
            );
    }

    #[Route('/contapyme/logout/{keyagent}', name: 'logout')]
    public function logout(string $keyagent): JsonResponse
    {
        $endpoint = $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/';

        $this->_arrParams[0] = '{}';
        $this->_arrParams[1] = $keyagent;
        $this->_arrParams[2] = $_ENV['API_IAPP'];
        $this->_arrParams[3] = (string)random_int(0,9);

        return new JsonResponse(
            [
                'path' => $endpoint,
                'method' => 'Logout',
                'parameters' => $this->_arrParams,
                'response' =>  $this->request($this->_arrParams, $endpoint)
            ]
            );
    }

    #[Route('/contapyme/request/{params}/{endpoint}', name: 'request')]
    public function request(array $params, string $endpoint): array
    {
        try{
            $response = $this->client->request('POST', $endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'body' => json_encode(['_parameters' => $params])
            ]);
            
            $this->logger->info('API request successful', [
                'endpoint' => $endpoint,
                'params' => $params,
                'response' => $response->toArray()
            ]);
           return $response->toArray();
        } catch (\Exception $e){
            return [
                'error' => $e->getMessage()
            ];
        }
        
    }
}
