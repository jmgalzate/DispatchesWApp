<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    
    public function __construct(
        private readonly OrderService $orderService,
    ) {}
    #[Route('/delivery', name: 'home_delivery')]
    public function index(): Response
    {
        return $this->render('components/DeliveryHome.html.twig', [
            'title' => 'Despachar Orden'
        ]);
    }
    
    #[Route('/delivery/{orderNumber}', name: 'delivery_order')]
    public function deliveryOrder($orderNumber): JsonResponse
    {
        $order = $this->orderService->process($orderNumber);
        $orderLoad = $this->orderService->load($orderNumber);
        
        if($orderLoad->getStatusCode() != 200) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al cargar la orden'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $orderData = $orderLoad->getContent();
        $orderArray = json_decode($orderData, true);
        
        return new JsonResponse([
            'status' => 'ok',
            'Order' => $orderArray,
        ], Response::HTTP_OK);
    }
    
    #[Route('/delivery/{orderNumber}/close', name: 'delivery_order_close')]
    public function deliveryOrderClose($orderNumber): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'message' => 'Orden cerrada correctamente'
        ], Response::HTTP_OK);
    }
    
    #[Route('/delivery/{orderNumber}/save', name: 'delivery_order_save')]
    public function deliveryOrderSave($orderNumber): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'message' => 'Orden guardada correctamente'
        ], Response::HTTP_OK);
    }

}