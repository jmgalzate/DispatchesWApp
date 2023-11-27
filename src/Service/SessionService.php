<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;

readonly class SessionService
{
    private string $status;
    private int $code;

    public function __construct (private readonly RequestStack $requestStack, private readonly ContapymeService $contapymeService) {
    }

    public function startSession (): JsonResponse {
        $response = $this->contapymeService->getAuth();
        $response = json_decode($response->getContent(), true);

        if ($response['Code'] === 200) {
            $this->requestStack->getSession()->set('keyAgent', $response['Response']['keyagente']);
            $this->status = 'Session started';
            $this->code = 200;
        } else {
            $this->status = 'Error al iniciar el agente: ' . $response['Response'];
            $this->code = $response['Code'];
        }

        return new JsonResponse([
            'Status' => $this->status,
            'Code' => $this->code
        ]);
    }

    public function closeSession (): JsonResponse {
        $logout = $this->contapymeService->logout();
        $logoutData = json_decode($logout->getContent(), true);

        $this->requestStack->getSession()->remove('keyAgent');
        $this->requestStack->getSession()->remove('products');

        if ($logoutData['Code'] === 200) {
            $this->status = 'Session closed';
            $this->code = 200;
        } else {
            $this->status = "Session closed, but consider check: " . $logoutData['Response'];
            $this->code = $logoutData['Code'];
        }

        return new JsonResponse([
            'Status' => "Session closed: " . $this->status,
            'Code' => $this->code
        ]);
    }
}
