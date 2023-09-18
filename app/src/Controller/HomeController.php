<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Service\ContapymeService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly SessionService $sessionService, private readonly RequestStack $requestStack
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
        $this->sessionService->startSession();
        return $this->redirectToRoute('homepage');
    }

    #[Route('/session/logout', name: 'app_session_logout')]
    public function logout(): Response
    {
        $this->sessionService->closeSession();
        return $this->redirectToRoute('homepage');
    }

    #[Route('/product/GET/all', name: 'get_all_products')]
    public function getProducts(ProductService $productService): Response
    {
        $loadProducts = $productService->getProducts();

        return $this->redirectToRoute('homepage');
    }
}
