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
        try {
            $response = $this->contapymeService->getAuth();
            $response = json_decode($response->getContent(), true);
            $this->requestStack->getSession()->set('keyagent', $response['keyagent']);
            $this->status = 'Success';
            $this->code = 200;
        } catch (\Throwable $th) {
            //$this->requestStack->getSession()->set('keyagent', 'Login error');
            $this->logger->error($th->getMessage());
            $this->status = 'Error';
            $this->code = 500;
        }

        return new JsonResponse([
            'Status' => $this->status,
            'Code' => $this->code
        ]);
    }

    public function closeSession(): void
    {
        try {
            $logout = $this->contapymeService->logout($this->requestStack->getSession()->get('keyagent'));
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
        }

        $this->requestStack->getSession()->remove('keyagent');
        $this->requestStack->getSession()->remove('products');
    }
}
