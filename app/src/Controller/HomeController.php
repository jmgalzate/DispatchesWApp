<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Service\ContapymeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly ContapymeService $contapymeService, private readonly RequestStack $requestStack
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('components/Home.html.twig', [
            'title' => 'Bienvenid@ al Portal de Despachos',
            'dispatchButton' => 'Nuevo despacho',
            'loadProductsButton' => 'Cargar productos',
        ]);
    }

    #[Route('/session/login', name: 'app_session_login')]
    public function login(): Response
    {
        try {
            $response = $this->contapymeService->getAuth();
            $response = json_decode($response->getContent(), true);
            $this->requestStack->getSession()->set('keyagent', $response['keyagent']);
        } catch (\Throwable $th) {
            $this->requestStack->getSession()->set('keyagent', 'Login error');
        }

        return $this->redirectToRoute('homepage');
    }

    #[Route('/session/logout', name: 'app_session_logout')]
    public function logout(): Response
    {
        try {
            $logout = $this->contapymeService->logout($this->requestStack->getSession()->get('keyagent'));
            $this->requestStack->getSession()->remove('keyagent');
            $this->requestStack->getSession()->remove('products');
            $response = 'No Session';
        } catch (\Throwable $th) {
            $response = 'Logout error';
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * TODO: 
     * - [x] Create methods to manage the Sessions
     * - [ ] Create methods to manage the Products on Session global variables
     */
}
