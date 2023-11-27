<?php

namespace App\Service;

use App\Entity\Message\Payload;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class ContapymeService
{
    private Payload $messagePayload;
    private array $actions = [
        1 => [
            "name" => "PROCESS",
            "messageType" => 6
        ],
        2 => [
            "name" => "UNPROCESS",
            "messageType" => 2
        ],
        3 => [
            "name" => "LOAD",
            "messageType" => 3
        ],
        4 => [
            "name" => "SAVE",
            "messageType" => 4
        ],
        5 => [
            "name" => "CALCULAR IMPUESTOS",
            "messageType" => 5
        ]
    ];

    /**
     * @throws Exception
     */
    public function __construct (
        private readonly MessagesService $messagesService,
        private readonly RequestStack $requestStack
    ) {
        $this->messagePayload = new Payload();
        $this->messagePayload->setIapp();
        $this->messagePayload->setRandom();
    }

    public function getAuth (): JsonResponse {
        $this->messagePayload->setAgent('');
        $this->messagePayload->setParameters([
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ]);

        $responseData = $this->messagesService->processRequest(
            messageType: 1,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData);

        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    public function logout (): JsonResponse {
        $this->messagePayload->setAgent($this->requestStack->getSession()->get('keyAgent'));
        $this->messagePayload->setParameters([]);

        $responseData = $this->messagesService->processRequest(
            messageType: 8,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData);

        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    public function action (int $actionId, int $orderNumber, array $newOrder = []): JsonResponse {
        $parameters = [
            'accion' => $this->actions[$actionId]['name'],
            'operaciones' => [
                [
                    'inumoper' => $orderNumber,
                    'itdoper' => $_ENV['API_ITDOPER']
                ]
            ]
        ];

        /*
         * If SAVE or CALCULAR IMPUESTOS, the newOrder parameter required.
         */

        if (in_array($actionId, [4, 5])) {
            $parameters['oprdata'] = $newOrder;
        }

        $this->messagePayload->setAgent($this->requestStack->getSession()->get('keyAgent'));
        $this->messagePayload->setParameters($parameters);

        $responseData = $this->messagesService->processRequest(
            messageType: $this->actions[$actionId]['messageType'],
            orderNumber: $orderNumber,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatOperaciones/"DoExecuteOprAction"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData);

        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    /*    
    
        The getAllProducts() is commented because the API returns a lot of products. When inserted the products in the DB
     the PHP memory limit exceeded. The solution: to get the products On Demand. The getRequestedProducts() function.
    
        public function getAllProducts (string $keyAgent): JsonResponse {
    
            $this->messagePayload->setAgent($keyAgent);
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
                orderNumber: 0,
                endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatElemInv/"GetListaElemInv"/',
                payload: $this->messagePayload
            );
    
            $validatedResponse = $this->validateResponse($responseData);
    
            return new JsonResponse([
                'Status' => $validatedResponse['Status'],
                'Code' => $validatedResponse['Code'],
                'Response' => $validatedResponse['Response']
            ]);
        }*/

    public function getRequestedProducts (array $products): JsonResponse {


        $quotedProducts = array_map(function ($product) {
            return "'$product'";
        }, $products);

        $this->messagePayload->setAgent($this->requestStack->getSession()->get('keyAgent'));
        $this->messagePayload->setParameters([
            "datospagina" => [
                "cantidadregistros" => $_ENV['API_QPRODUCTS']
            ],
            "camposderetorno" => [
                "irecurso",
                "nrecurso",
                "clase2"
            ],
            "datosfiltro" => [
                "sql" => "clase2 is not null and clase2 <> '' and irecurso in (" . implode(',', $quotedProducts) . ")"
            ],
            "ordernarpor" => [
                "clase2" => "asc"
            ]
        ]);

        $responseData = $this->messagesService->processRequest(
            messageType: 7,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatElemInv/"GetListaElemInv"/',
            payload: $this->messagePayload
        );

        $validatedResponse = $this->validateResponse($responseData);

        return new JsonResponse([
            'Status' => $validatedResponse['Status'],
            'Code' => $validatedResponse['Code'],
            'Response' => $validatedResponse['Response']
        ]);
    }

    /**
     * This function confirms if accepted message. The API returns the HTTP code in the body.
     */

    private function validateResponse (array $responseData): array {
        if ($responseData['Status'] === 'Success') {
            $response = $responseData['Response'];

            //Anonymous function for validating the response
            $validateResponse = function ($response) {
                $header = $response['result'][0]['encabezado'];
                $body = $response['result'][0]['respuesta'];

                if ($header['resultado'] === "true") {
                    return [
                        'Status' => 'Success',
                        'Code' => 200, //Value set as default due to the API doesn't return a code when message
                        // accepted. 
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

            $validatedResponse = $validateResponse($response); //Calling the anonymous function

            return [
                'Status' => $validatedResponse['Status'],
                'Code' => $validatedResponse['Code'],
                'Response' => $validatedResponse['Response']['datos'] ?? $validatedResponse['Response']
            ];

        } else {
            return [
                'Status' => 'Error',
                'Code' => $responseData['Code'],
                'Response' => $responseData['Response']
            ];
        }
    }
}
