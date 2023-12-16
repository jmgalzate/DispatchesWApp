<?php

namespace App\Service;

use App\Entity\Message\Payload;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class ContapymeService
{
    private Payload $messagePayload;
    private string $keyAgent;
    private array $actions = [
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
        ],
        6 => [
            "name" => "PROCESS",
            "messageType" => 6
        ],
    ];

    /**
     * @throws Exception
     */
    public function __construct (
        private readonly MessagesService $messagesService,
        private readonly RequestStack    $requestStack
    ) {
        $this->messagePayload = new Payload();
        $this->messagePayload->setIapp();
        $this->messagePayload->setRandom();

        $this->keyAgent = $this->requestStack->getSession()->get('keyAgent') ?? '';
    }

    public function activateAgent (): array {

        $this->messagePayload->setAgent('');

        $this->messagePayload->setParameters([
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ]);

        $request = $this->messagesService->processRequest(
            messageType: 1,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/',
            payload: $this->messagePayload
        );

        if ($request['code'] === Response::HTTP_INTERNAL_SERVER_ERROR)
            return $request;

        return $this->validateResponse($request['body']);
    }
    public function closeAgent (): array {
        $this->messagePayload->setAgent($this->keyAgent);
        $this->messagePayload->setParameters([]);

        $request = $this->messagesService->processRequest(
            messageType: 8,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/',
            payload: $this->messagePayload
        );

        if ($request['code'] === Response::HTTP_INTERNAL_SERVER_ERROR)
            return $request;

        return $this->validateResponse($request['body']);
    }
    public function agentAction (int $actionId, int $orderNumber, array $newOrder = []): array {
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

        $this->messagePayload->setAgent($this->keyAgent);
        $this->messagePayload->setParameters($parameters);

        $request = $this->messagesService->processRequest(
            messageType: $this->actions[$actionId]['messageType'],
            orderNumber: $orderNumber,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatOperaciones/"DoExecuteOprAction"/',
            payload: $this->messagePayload
        );

        if ($request['code'] === Response::HTTP_INTERNAL_SERVER_ERROR)
            return $request;

        return $this->validateResponse($request['body']);
    }
    public function getRequestedProducts (array $products): array {

        $quotedProducts = array_map(function ($product) {
            return "'$product'";
        }, $products);

        $this->messagePayload->setAgent($this->keyAgent);
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

        $request = $this->messagesService->processRequest(
            messageType: 7,
            orderNumber: 0,
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TCatElemInv/"GetListaElemInv"/',
            payload: $this->messagePayload
        );

        if ($request['code'] === Response::HTTP_INTERNAL_SERVER_ERROR)
            return $request;

        return $this->validateResponse($request['body']);
    }
    private function validateResponse (string $response): array {
        
        //TODO: validate how to handle the JSON_DECODE exception
        $responseData = json_decode($response, true, 512, JSON_THROW_ON_ERROR |
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


        //Anonymous function for validating the response
        $validateResponse = function ($response) {
            $header = $response['result'][0]['encabezado'];

            if ($header['resultado'] === "true") {
                return [
                    'code' => Response::HTTP_OK,
                    'body' => $response['result'][0]['respuesta']
                ];
            } else {
                
                if($header['imensaje'] === "40")
                    return [
                        'code' => Response::HTTP_BAD_REQUEST,
                        'body' => $header['mensaje']
                    ];
                else 
                    return [
                        'code' => intval($header['imensaje']),
                        'body' => $header['mensaje']
                    ];
            }
        };

        $validatedResponse = $validateResponse($responseData); //Calling the anonymous function

        return [
            'code' => $validatedResponse['code'],
            'body' => $validatedResponse['body']
        ];
    }
}
