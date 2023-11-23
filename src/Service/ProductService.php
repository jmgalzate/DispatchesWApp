<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct (
        private readonly ContapymeService       $contapymeService,
        private readonly RequestStack           $requestStack,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getProducts (string $keyAgent = null): int {

        $agent = $this->requestStack->getSession()->get('keyAgent') ?? $keyAgent;

        $response = $this->contapymeService->getProducts(
            keyAgent: $agent
        );

        $responseData = json_decode($response->getContent(), true);

        $productCount = 0;
        foreach ($responseData['Response'] as $productData) {
            $product = new Product(
                name: $productData['nrecurso'],
                barcode: $productData['clase2'],
                code: $productData['irecurso'],
                requestedquantity: 0,
                dispatchedquantity: 0,
            );

            $this->saveProduct($product);
            unset($product); // Free up memory
            $productCount++;
        }

        unset($responseData); // Free up memory

        return $productCount;
    }

    public function findProductByBarcode (string $barcode) {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['barcode' => $barcode]);
    }

    public function findProductByCode (string $code) {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code]);
    }

    private function saveProduct (Product $product): void {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

}