<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductService
{
    public function __construct (
        private ContapymeService       $contapymeService,
        private EntityManagerInterface $entityManager,
    ) {
    }
    
    //TODO: implement this method from Controller

    public function getProducts (array $products): int {

        $response = $this->contapymeService->getRequestedProducts(
            products: $products
        );

        $responseData = json_decode($response->getContent(), true);

        $productCount = 0;
        foreach ($responseData['Response'] as $productData) {
            $product = new Product(
                name: $productData['nrecurso'],
                barcode: $productData['clase2'],
                code: $productData['irecurso']
            );

            $this->saveProduct($product);
            unset($product); // Free up memory
            $productCount++;
        }
        unset($responseData); // Free up memory
        return $productCount;
    }

    public function findProductByBarcode (string $barcode): Product | null {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['barcode' => $barcode]);
    }

    public function findProductByCode (string $code): Product | null {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code]);
    }
    
    public function totalProductsInDB (): int {
        return $this->entityManager->getRepository(Product::class)->totalProductsInDB();
    }

    private function saveProduct(Product $product): void {
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