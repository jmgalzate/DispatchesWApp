<?php 

namespace App\Controller;

use App\Service\ContapymeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ContapymeController extends AbstractController
{

    public function __construct(
        private readonly ContapymeService $contapymeService
    )
    {
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
        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    #[Route('/contapyme/logout', name: 'contapyme_logout')]
    public function logout(string $keyagent): JsonResponse
    {

        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    #[Route('/contapyme/action={actionid}/order={order}', name: 'contapyme_action')]
    public function action(int $actionid, int $order, string $keyagent, array $newOrder = []): JsonResponse
    {
        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }

    #[Route('/contapyme/products', name: 'contapyme_products')]
    public function products(string $keyagent): JsonResponse
    {

        return new JsonResponse([
            'response' => 'Response' //TODO: update this
        ]);
    }
}