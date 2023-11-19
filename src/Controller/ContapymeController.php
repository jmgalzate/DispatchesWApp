<?php 

namespace App\Controller;

use App\Entity\Message\Payload;
use App\Service\MessagesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ContapymeController extends AbstractController
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
        private readonly MessagesService $messagesService
    )
    {
        $this->messagePayload = new Payload();
        $this->messagePayload->setIapp();
        $this->messagePayload->setRandom();
    }

    #[Route('/contapyme', name: 'contapyme')]
    public function index(): JsonResponse {

        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ContapymeController.php',
        ]);
    }

    #[Route('/contapyme/auth', name: 'contapyme_auth')]
    public function getAuth(): JsonResponse
    {
        $this->messagePayload->setAgent('');
        $this->messagePayload->setParameters([
            'email' => $_ENV['API_USERNAME'],
            'password' => md5($_ENV['API_PASSWORD']),
            'id_maquina' => $_ENV['API_MACHINE_ID']
        ]);

        $responseData = $this->messagesService->processRequest(
            messageType:1, 
            orderNumber: null, 
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"GetAuth"/', 
            payload: $this->messagePayload
        );

        return new JsonResponse([
            $responseData
        ]);
    }

    #[Route('/contapyme/logout', name: 'contapyme_logout')]
    public function logout(string $keyagent): JsonResponse
    {
        $this->messagePayload->setAgent($keyagent);
        $this->messagePayload->setParameters([]);
        
        $responseData = $this->messagesService->processRequest(
            messageType:8, 
            orderNumber: null, 
            endpoint: $_ENV['API_SERVER_HOST'] . 'datasnap/rest/TBasicoGeneral/"Logout"/', 
            payload: $this->messagePayload
        );

        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    #[Route('/contapyme/action={actionid}/order={order}', name: 'contapyme_action')]
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

        if(in_array($actionid, [4, 5])) {
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

    #[Route('/contapyme/products', name: 'contapyme_products')]
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
}