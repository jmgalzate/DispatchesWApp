<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class OrderService
{
    /**
     * TODO: 
     * 1. Manage exceptions for each step, for example when the order doesn't exist.
     * 2. If the response is that the agent is not connected, then try to connect and try again.
     */
    public function __construct (
        private ContapymeService $contapymeService,
        private RequestStack $requestStack
    ) {}
    
    public function process(string $orderNumber): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 1,
            orderNumber: $orderNumber
        );
        
        $responseData = $response->getContent();
        
        return new JsonResponse([
            'message' => 'Order processed successfully!',
            'data' => $responseData
        ]);
    }
    
    public function load(string $orderNumber): JsonResponse {
        
        $response = $this->contapymeService->action(
            actionId: 3,
            orderNumber: $orderNumber
        );
        
        $responseData = $response->getContent();
        $responseData = json_decode($responseData, true);

        // Deserialize the JSON data into an Order object
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(null, null, null , new ReflectionExtractor())];
        $serializer = new Serializer($normalizers, $encoders);
        $order = $serializer->deserialize(json_encode($responseData['Response']), Order::class, 'json');
        // End of deserialization
        
        $this->requestStack->getSession()->set('order', $order);
        
        return new JsonResponse([
            'message' => 'Order loaded successfully!',
        ]);
    }
    
    public function save(string $orderNumber, array $newOrderData): JsonResponse {
        $response = $this->contapymeService->action(
            actionId: 4,
            orderNumber: $orderNumber,
            newOrder: $newOrderData
        );

        $responseData = $response->getContent();

        return new JsonResponse([
            'message' => 'Order saved successfully!',
            'data' => $responseData
        ]);
    }
    
    public function taxes(string $orderNumber, array $newOrderData): JsonResponse {
        $response = $this->contapymeService->action(
            actionId: 5,
            orderNumber: $orderNumber, 
            newOrder: $newOrderData
        );

        $responseData = $response->getContent();

        return new JsonResponse([
            'message' => 'Taxes calculated successfully!',
            'data' => $responseData
        ]);
    }
    
    public function unprocess(string $orderNumber): JsonResponse {
        $response = $this->contapymeService->action(
            actionId: 2,
            orderNumber: $orderNumber
        );

        $responseData = $response->getContent();

        return new JsonResponse([
            'message' => 'Order unprocessed successfully!',
            'data' => $responseData
        ]);
    }
    
    public function close(): JsonResponse {

        $this->requestStack->getSession()->remove('order');
        $this->requestStack->getSession()->remove('dispatch');

        return new JsonResponse([
            'message' => 'Order closed successfully!'
        ]);
    }
}