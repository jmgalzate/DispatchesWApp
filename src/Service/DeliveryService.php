<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Product;

class DeliveryService
{
    private ContapymeService $contapymeService;

    public function __construct(ContapymeService $contapymeService, private readonly RequestStack $requestStack)
    {
        $this->contapymeService = $contapymeService;
    }

    /**
     * @throws \JsonException
     */
    public function getOrder(string $orderNumber): array
    {
        $keyagent = $this->requestStack->getSession()->get('keyagent');
        $order = $this->contapymeService->action(action: 'LOAD', keyagent: $keyagent, order: $orderNumber);
        return json_decode($order->getContent(), true);
    }

    public function getProducts(): array
    {
        $keyagent = $this->requestStack->getSession()->get('keyagent');
        $productsData = $this->contapymeService->getProducts(keyagent: $keyagent, cant: 50);

        $productsData = $productsData->getContent();
        $productsData = json_decode($productsData, true);
        $productsData = $productsData['body'];

        $products = []; // Create an empty array to store products
        $id = 0;

        try {
            foreach ($productsData as $productData) {
                // Create a new Product object and add it to the $products array
                $product = new Product(
                    id: $id++,
                    name: $productData['nrecurso'],
                    barcode: $productData['clase2'],
                    productcode: $productData['irecurso'],
                    quantity: 1
                );
                $products[] = $product;
            }

            // Now $products array contains all the Product objects
            return $products;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    private function handlingOrder(array $order): array
    {
        //$order['order']['delivery'] = $this->getDelivery($order['order']['delivery']);
        return $order;
    }

}