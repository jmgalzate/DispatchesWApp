<?php

namespace App\Service;

use Exception;
use App\Controller\ContapymeController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeliveryService
{
    private ContapymeController $contapymeController;

    public function __construct(ContapymeController $contapymeController)
    {
        $this->contapymeController = $contapymeController;
    }

    public function getOrder(string $orderNumber): array
    {
        $order = $this->contapymeController->action(action: 'LOAD', keyagent: $_COOKIE["keyagent"], order: $orderNumber);
        return json_decode($order->getContent(), true);
    }

}