<?php

namespace App\Service;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class OrderService
{
    public function __construct (
        private ContapymeService $contapymeService,
        private RequestStack $requestStack,
        private ProductService $productService
    ) {}
    
    public function process(string $orderNumber): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 1,
            orderNumber: $orderNumber
        );

        return new JsonResponse([], $response->getStatusCode());
    }
    
    public function load(string $orderNumber): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 3,
            orderNumber: $orderNumber
        );
        
        $responseData = $response->getContent();
        $responseData = json_decode($responseData, true);

        // Deserializing data into an App\Entity\Order object.
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, null, null , new ReflectionExtractor())];
        $serializer = new Serializer($normalizers, $encoders);
        $order = $serializer->deserialize(json_encode($responseData['Response']), Order::class, 'json');
        // End of deserialization
        
        $this->productService->setProductsLists($order->getListaproductos());
        
        return new JsonResponse([], $response->getStatusCode());
    }
    
    public function save(string $orderNumber, array $newOrderData): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 4,
            orderNumber: $orderNumber,
            newOrder: $newOrderData
        );

        return new JsonResponse([], $response->getStatusCode());
    }
    
    public function taxes(string $orderNumber, array $newOrderData): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 5,
            orderNumber: $orderNumber, 
            newOrder: $newOrderData
        );

        return new JsonResponse([], $response->getStatusCode());
    }
    
    public function unprocess(string $orderNumber): JsonResponse {
        $response = $this->contapymeService->action(
            actionId: 2,
            orderNumber: $orderNumber
        );

        return new JsonResponse([], $response->getStatusCode());
    }
    
    public function close(): JsonResponse {

        $this->requestStack->getSession()->remove('order');
        $this->requestStack->getSession()->remove('dispatch');
        $this->requestStack->getSession()->remove('productsToDispatch');

        return new JsonResponse([], Response::HTTP_OK);
    }
}