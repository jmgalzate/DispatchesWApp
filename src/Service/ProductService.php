<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class ProductService
{
    public function __construct (
        private ContapymeService       $contapymeService,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack
    ) {
    }

    #[NoReturn] public function setProductsLists (array $productsList): void {
        $products = [];

        /** Get products, and validate if repeated products for establish the quantities. */
        foreach ($productsList as $productData) {
            if (empty($products)) {
                $products[] = [
                    'code' => $productData['irecurso'],
                    'quantity' => $productData['qrecurso']
                ];
            } else {
                foreach ($products as $product) {
                    if ($product['code'] === $productData['irecurso']) {
                        $product['quantity'] += $productData['qrecurso'];
                    } else {
                        $products[] = [
                            'code' => $productData['irecurso'],
                            'quantity' => $productData['qrecurso']
                        ];
                    }
                }
            }
        }

        /** Get products codes for request to Contapyme */
        $productCodes = [];
        foreach ($products as $productData) {
            $productCodes[] = $productData['code'];
        }

        $productsRecorded = $this->getContapymeProducts($productCodes);

        /** Set products to dispatch and record it as a cookie */
        $productsToDispatch = [];
        
        $id = 0;
        foreach ($productsRecorded as $product) {
            $productsToDispatch[] = new \App\Entity\Delivery\Product(
                name: $product->getName(),
                barcode: $product->getBarcode(),
                code: $product->getCode(),
                requestedQuantity: 0,
                deliveredQuantity: 0
            );
        }

        foreach ($products as $productData) {
            foreach ($productsToDispatch as $product) {
                if ($product->getCode() === $productData['code']) {
                    $product->setRequestedQuantity($productData['quantity']);
                }
            }
        }
        
        $this->requestStack->getSession()->set('productsToDispatch', $productsToDispatch);  
        
    }

    /**
     * It requires products list passed as an array of strings.
     * Example for calling the method: getContapymeProducts(products: ['irecurso1', 'irecurso2', 'irecurso3'])
     */

    private function getContapymeProducts (array $productsToSearch): array {

        $products = [];
        $response = $this->contapymeService->getRequestedProducts(
            products: $productsToSearch
        );

        $responseData = json_decode($response->getContent(), true);

        foreach ($responseData['Response'] as $productData) {
            $product = new Product(
                name: $productData['nrecurso'],
                barcode: $productData['clase2'],
                code: $productData['irecurso']
            );

            $products[] = $product;

            $this->saveProduct($product);
            unset($product); // Free up memory
        }
        unset($responseData); // Free up memory
        return $products;
    }

    public function findProductByBarcode (string $barcode): Product|null {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['barcode' => $barcode]);
    }

    public function findProductByCode (string $code): Product|null {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code]);
    }

    public function totalProductsInDB (): int {
        return $this->entityManager->getRepository(Product::class)->totalProductsInDB();
    }

    private function saveProduct (Product $product): void {
        $existingProduct = $this->findProductByCode($product->getCode());

        if ($existingProduct) {
            // Update existing product
            $existingProduct->setName($product->getName());
            $existingProduct->setBarcode($product->getBarcode());
        } else {
            // Insert new product
            $this->entityManager->persist($product);
        }

        $this->entityManager->flush();
    }

}