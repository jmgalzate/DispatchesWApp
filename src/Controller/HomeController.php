<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ProductService;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('components/Home.html.twig', [
            'title' => 'Bienvenid@ al Portal de Despachos',
            'dispatchButton' => 'Nuevo despacho',
            'loadProductsButton' => 'Cargar productos',
        ]);
    }

    #[Route('/GET/products', name: 'homepage_get_products')]
    public function getProducts(ProductService $productService): Response
    {
        $loadProducts = $productService->getProducts();

        return $this->redirectToRoute('homepage', $loadProducts);
    }
}
