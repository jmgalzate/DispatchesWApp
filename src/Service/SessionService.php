<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SessionService
{
    private string $status;
    private int $code;
    public function __construct(private readonly RequestStack $requestStack, private readonly ContapymeService $contapymeService, private readonly LoggerInterface $logger)
    {
    }

    public function startSession(): JsonResponse
    {
        $response = $this->contapymeService->getAuth();
        $response = json_decode($response->getContent(), true);

        if ($response['Code'] === 200) {
            $this->requestStack->getSession()->set('keyagent', $response['Response']['keyagente']);
            $this->status = 'Session started';
            $this->code = 200;
        } else {
            $this->status = 'Error: ' . $response['Response'];
            $this->code = $response['Code'];
        }

        return new JsonResponse([
            'Status' => $this->status,
            'Code' => $this->code
        ]);
    }

    public function closeSession(): JsonResponse
    {
        $logout = $this->contapymeService->logout($this->requestStack->getSession()->get('keyagent'));
        $logoutData = json_decode($logout->getContent(), true);

        $this->requestStack->getSession()->remove('keyagent');
        $this->requestStack->getSession()->remove('products');

        if ($logoutData['Code'] === 200) {
            $this->status = 'Session closed';
            $this->code = 200;
        } else {
            $this->status = "Session closed, but consider check: \n" . $logoutData['Response'];
            $this->code = $logoutData['Code'];
        }

        return new JsonResponse([
            'Status' => "Session closed. \n".$this->status,
            'Code' => $this->code
        ]);
    }
}
