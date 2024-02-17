<?php

namespace App\Controller;

use App\Service\ContapymeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ContapymeService $contapymeService
    ) {
    }

    #[Route('api/session/login', name: 'app_session_login', methods: ['GET'])]
    public function login(Request $request): JsonResponse
    {
        if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $authRequest = $this->contapymeService->activateAgent();

        if($authRequest['code'] === Response::HTTP_OK) {
            $response = [
                'message' => 'Agente iniciado correctamente',
                'code' => Response::HTTP_OK
            ];

            $this->requestStack->getSession()->set('keyAgent', $authRequest['body']['datos']['keyagente']);

        } else {
            $response = [
                'message' => $authRequest['body'],
                'code' => $authRequest['code']
            ];
        }

        $jsonResponse = new JsonResponse($response['message']);
        $jsonResponse->setStatusCode($response['code']);

        return $jsonResponse;
    }

    #[Route('api/session/logout', name: 'app_session_logout', methods: ['GET'])]
    public function logout(Request $request): Response
    {
        if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $logoutRequest = $this->contapymeService->closeAgent();

        if($logoutRequest['code'] === Response::HTTP_OK) {
            $response = [
                'message' => 'Agente cerrado correctamente',
                'code' => Response::HTTP_OK
            ];

            $this->requestStack->getSession()->remove('keyAgent');

        } else {
            $response = [
                'message' => $logoutRequest['body'],
                'code' => $logoutRequest['code']
            ];
        }

        $jsonResponse = new JsonResponse($response['message']);
        $jsonResponse->setStatusCode($response['code']);

        return $jsonResponse;
    }
}
