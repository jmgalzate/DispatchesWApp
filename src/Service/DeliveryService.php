<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

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
        $products = $this->contapymeService->getProducts(keyagent: $keyagent, cant: 5);
        return json_decode($products->getContent(), true);
    }

    private function handlingOrder(array $order): array
    {
        //$order['order']['delivery'] = $this->getDelivery($order['order']['delivery']);
        return $order;
    }

}