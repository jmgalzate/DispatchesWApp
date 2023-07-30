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

    public function getOrder(string $orderNumber): array
    {
        $keyagent = $this->requestStack->getSession()->get('keyagent');
        $order = $this->contapymeService->action(action: 'LOAD', keyagent: $keyagent, order: $orderNumber);
        return json_decode($order->getContent(), true);
    }

    private function handlingOrder(array $order): array
    {
        //$order['order']['delivery'] = $this->getDelivery($order['order']['delivery']);
        return $order;
    }

}