<?php

namespace App\Controller;

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

    #[Route('/session', name: 'app_session')]
    public function index(): Response
    {
        return new Response(
            '<html><body>
            <h1>Session</h1>
            <ul>
                <li><a href="/session/login">Login</a></li>
                <li><a href="/session/logout">Logout</a></li>
            </ul>
        </body></html>'
        );
    }

    #[Route('/session/login', name: 'app_session_login')]
    public function login(): Response
    {
        $response = $this->sessionService->startSession();
        $responseData = json_decode($response->getContent(), true);

        if ($responseData['Code'] !== 200) {
            $message = "Session error: " . str_replace('"', '\"', $responseData['Status']);
            return new Response(
                '<html><body>
                <script>
                    alert("' . $message . '");
                    window.location.href = "/session";
                </script>
            </body></html>'
            );
        }

        return $this->redirect('/session');
    }

    #[Route('/session/logout', name: 'app_session_logout')]
    public function logout(): Response
    {
        $response = $this->sessionService->closeSession();
        $responseData = json_decode($response->getContent(), true);

        if ($responseData['Code'] !== 200) {
            $message = "Session error: " . str_replace('"', '\"', $responseData['Status']);
            return new Response(
                '<html><body>
                <script>
                    alert("' . $message . '");
                    window.location.href = "/session";
                </script>
            </body></html>'
            );
        }

        return new Response(
            '<html><body>
            <script>
                alert("'.$responseData['Status'].'");
                window.location.href = "/session";
            </script>
        </body></html>'
        );
    }
}
