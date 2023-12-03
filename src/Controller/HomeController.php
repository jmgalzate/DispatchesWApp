<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ProductService;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        
        return $this->render('components/Home.html.twig', [
            'title' => 'Bienvenid@ al Portal de Despachos',
            'dispatchButton' => 'Nuevo despacho',
            'totalProducts' => $this->productService->totalProductsInDB()
        ]);
    }
}
