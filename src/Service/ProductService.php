<?php

/**
 * TODO: remove this Service and manage some methods from HomeController and other by DeliveryController
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;
use App\Entity\ProductEntity;

class ProductService{

    private ContapymeService $contapymeService;
    private int $nextProductId = 0;

    public function __construct(ContapymeService $contapymeService, private readonly RequestStack $requestStack)
    {
        $this->contapymeService = $contapymeService;
    }

    public function getProducts(): array
    {
        $keyagent = $this->requestStack->getSession()->get('keyagent');
        $productsData = $this->contapymeService->getProducts(keyagent: $keyagent, cant: $_ENV['API_QPRODUCTS']);

        $productsData = $productsData->getContent();
        $productsData = json_decode($productsData, true);
        $productsData = $productsData['body'];

        $products = []; // Create an empty array to store products

        try {
            foreach ($productsData as $productData) {
                // Create a new Product object and add it to the $products array
                $product = new Product(
                    id: $this->nextProductId++,
                    name: $productData['nrecurso'],
                    barcode: $productData['clase2'],
                    code: $productData['irecurso'],
                    requestedquantity: 0,
                    dispatchedquantity: 0
                );
                $products[] = $product;
            }

            $responseData = [];
            foreach ($products as $product) {
                $responseData[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'barcode' => $product->getBarcode(),
                    'code' => $product->getCode(),
                ];
            }

            $this->requestStack->getSession()->set('products', $responseData);

            // Now $products array contains all the Product objects
            return [
                'success' => 'Productos cargados correctamente'
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function vlookupProduct(string $barcode): array
    {
        $products = $this->requestStack->getSession()->get('products');
        $product = array_filter($products, function ($product) use ($barcode) {
            return $product['barcode'] === $barcode;
        });

        if (count($product) > 0) {
            $product = array_values($product)[0];
            return [
                'success' => 'Producto encontrado',
                'product' => $product
            ];
        } else {
            return [
                'error' => 'Producto no encontrado'
            ];
        }
    }
}