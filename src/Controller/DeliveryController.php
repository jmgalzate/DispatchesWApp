<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DeliveryService;

class DeliveryController extends AbstractController
{
    #[Route('/delivery', name: 'home_delivery')]
    public function index(): Response
    {
        return $this->render('components/Delivery.html.twig', [
            'controller_name' => 'DeliveryController',
        ]);
    }

    #[Route('/delivery/{orderNumber}', name: 'delivery')]
    public function delivery(string $orderNumber, DeliveryService $deliveryService): JsonResponse
    {
        $order = $deliveryService->getOrder($orderNumber);
        return new JsonResponse($order);
    }
}
