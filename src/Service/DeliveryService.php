<?php
namespace App\Service;

class DeliveryService
{
    private ContapymeService $contapymeService;
    private array $order;

    public function __construct(ContapymeService $contapymeService)
    {
        $this->contapymeService = $contapymeService;
    }

    public function getOrder(string $orderNumber): array
    {
        $order = $this->contapymeService->action(action: 'LOAD', keyagent: $_COOKIE["keyagent"], order: $orderNumber);
        return json_decode($order->getContent(), true);
    }
}