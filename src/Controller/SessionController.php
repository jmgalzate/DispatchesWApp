<?php

namespace app\Controller;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    public function __construct(
        private readonly SessionService $sessionService
    ) {
    }

    #[Route('/session/login', name: 'app_session_login')]
    public function login(): Response
    {
        $this->sessionService->startSession();
        return $this->redirectToRoute('homepage');
    }

    #[Route('/session/logout', name: 'app_session_logout')]
    public function logout(): Response
    {
        $this->sessionService->closeSession();
        return $this->redirectToRoute('homepage');
    }
}