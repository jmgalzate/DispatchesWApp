<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProductService
{
    public function __construct (
        private ContapymeService       $contapymeService,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param array $productsList App\Entity\Delivery\Product
     * @return array App\Entity\Delivery
     */
    public function setProductsLists (array $productsList): array {
        $products = [];
        $productsCodes = [];

        foreach ($productsList as $productData) {
            $found = false;
            
            foreach ($products as $product) {
                
                if ($product['code'] === $productData->irecurso) {
                    
                    $product['quantity'] += $productData->qrecurso;
                    
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $products[] = [
                    'code' => $productData->irecurso,
                    'quantity' => $productData->qrecurso
                ];
                
                $productsCodes[] = $productData->irecurso;
            }
        }

        $productsRecorded = $this->getContapymeProducts($productsCodes);

        /** Set products to dispatch */
        $productsToDispatch = [];
        
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

        return $productsToDispatch;
    }

    private function getContapymeProducts (array $productsToSearch): array {
        
        $products = [];
        $response = $this->contapymeService->getRequestedProducts(
            products: $productsToSearch
        );

        foreach ($response['body']['datos'] as $productData) {
            $product = new Product(
                name:       trim($productData['nrecurso']),
                barcode:    trim($productData['clase2']),
                code:       trim($productData['irecurso'])
            );

            $products[] = $product;

            $this->entityManager->getRepository(Product::class)->saveOrUpdate($product);
            unset($product); // Free up memory
        }
        
        return $products;
    }
}