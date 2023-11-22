<?php

namespace App\Service;

use App\Entity\Message\Payload;
use App\Service\MessagesService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContapymeService
{
    private Payload $messagePayload;
    private array $actions = [
        1 => [
            "name" => "PROCESS",
            "messageType"   => 6
        ],
        2 => [
            "name" => "UNPROCESS",
            "messageType"   => 2
        ],
        3 => [
            "name" => "LOAD",
            "messageType"   => 3
        ],
        4 => [
            "name" => "SAVE",
            "messageType"   => 4
        ],
        5 => [
            "name" => "CALCULAR IMPUESTOS",
            "messageType"   => 5
        ]
    ];

    public function __construct(
        private readonly MessagesService $messagesService,
        private readonly LogService $logService
    ) {
        $this->messagePayload = new Payload();
        $this->messagePayload->setIapp();
        $this->messagePayload->setRandom();
    }

    public function getAuth(): JsonResponse
    {
        $this->messagePayload->setAgent('');
        $this->messagePayload->setParameters([
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ]);

        $responseData = $this->messagesService->processRequest(
            messageType: 1,
            orderNumber: null,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData['Response']);
        
        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    public function logout(string $keyagent): JsonResponse
    {
        $this->messagePayload->setAgent($keyagent);
        $this->messagePayload->setParameters([]);

        $responseData = $this->messagesService->processRequest(
            messageType: 8,
            orderNumber: null,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData['Response']);

        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    public function action(int $actionid, int $order, string $keyagent, array $newOrder = []): JsonResponse
    {
        $parameters = [
            'accion' => $this->actions[$actionid]['name'],
            'operaciones' => [
                [
                    'inumoper' => $order,
                    'itdoper' => $_ENV['API_ITDOPER']
                ]
            ]
        ];

        if (in_array($actionid, [4, 5])) {
            $parameters['oprdata'] = $newOrder;
        }

        $this->messagePayload->setAgent($keyagent);
        $this->messagePayload->setParameters($parameters);

        $responseData = $this->messagesService->processRequest(
            messageType: $this->actions[$actionid]['messageType'],
            orderNumber: $order,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatOperaciones/"DoExecuteOprAction"/',
            payload: $this->messagePayload
        );

        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    public function products(string $keyagent): JsonResponse
    {
        $this->messagePayload->setAgent($keyagent);
        $this->messagePayload->setParameters([
            "datospagina" => [
                "cantidadregistros" => $_ENV['API_QPRODUCTS'],
                "pagina" => ""
            ],
            "camposderetorno" => [
                "irecurso",
                "nrecurso",
                "clase2"
            ]
        ]);

        $responseData = $this->messagesService->processRequest(
            messageType: 7,
            orderNumber: null,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatProductos/"GetAllProducts"/',
            payload: $this->messagePayload
        );

        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    /**
     * This validateReponse function is created for confirming if the message was accepted, because the API returns the error code in the body and it is not configured for HTTP status codes.
     */

     private function validateResponse(array $responseData): array
     {
         if ($responseData['Status'] === 'Success') {
             $response = $responseData['Response'];

             $validateResponse = function ($response) {
                 $header = $response['result'][0]['encabezado'];
                 $body = $response['result'][0]['respuesta'];
     
                 if ($header['resultado'] === "true") {
                     return [
                         'Status' => 'Success',
                         'Code' => 200, //The value is set as default due to the API doesn't return a code when the message is accepted. 
                         'Response' => $body
                     ];
                 } else {
                     return [
                         'Status' => 'Error',
                         'Code' => intval($header['imensaje']),
                         'Response' => $header['mensaje']
                     ];
                 }
             };
     
             $validatedResponse = $validateResponse($response);
     
             if ($validatedResponse['Status'] === 'Success') {
                 return [
                     'Status' => $validatedResponse['Status'],
                     'Code' => $validatedResponse['Code'],
                     'Response' => [
                         'keyagent' => $validatedResponse['Response']['datos']['keyagente'],
                     ]
                 ];
             } else {
                 return [
                     'Status' => 'Error',
                     'Code' => $validatedResponse['Code'],
                     'Response' => $validatedResponse['Response']
                 ];
             }
         } else {
             return [
                 'Status' => 'Error',
                 'Code' => $responseData['Code'],
                 'Response' => $responseData['Response']
             ];
         }
     }
}
