<?php 

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;

class SessionService
{
    public function __construct(private readonly RequestStack $requestStack, private readonly ContapymeService $contapymeService, private readonly LoggerInterface $logger)
    {
    }

    public function startSession () : void {
        try {
            $response = $this->contapymeService->getAuth();
            $response = json_decode($response->getContent(), true);
            $this->requestStack->getSession()->set('keyagent', $response['keyagent']);
        } catch (\Throwable $th) {
            $this->requestStack->getSession()->set('keyagent', 'Login error');
            $this->logger->error($th->getMessage());
        }
    }

    public function closeSession () : void {
        try {
            $logout = $this->contapymeService->logout($this->requestStack->getSession()->get('keyagent'));
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
        }

        $this->requestStack->getSession()->remove('keyagent');
        $this->requestStack->getSession()->remove('products');
    }
}