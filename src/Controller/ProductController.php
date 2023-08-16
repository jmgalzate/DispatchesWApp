<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): JsonResponse
    {
        return new JsonResponse(
            "This is the product page"
        );
    }

    #[Route('/product/GET/all', name: 'get_all_products')]
    public function getProducts(ProductService $productService): Response
    {
        $loadProducts = $productService->getProducts();

        return $this->redirectToRoute('homepage', $loadProducts);
    }

    #[Route('/product/GET/barcode:{barcode}', name: 'get_product_by_barcode')]
    public function getProduct(ProductService $productService, string $barcode): Response
    {
        $loadProduct = $productService->vlookupProduct($barcode);

        return $this->redirectToRoute('homepage', $loadProduct);
    }
}
