<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{

    public function __construct () { }

    #[Route('/delivery', name: 'home_delivery')]
    public function index (): Response {
        return $this->render('components/Delivery/Home.html.twig', [
            'title' => 'Despachar Orden'
        ]);
    }

    #[Route('/delivery/{orderNumber}', name: 'delivery_order')]
    public function deliveryOrder ($orderNumber): Response {

        $order = [
            'orderNumber' => $orderNumber,
            'customerName' => 'Juan',
            'customerNIT' => '90077886655',
            'products' => [
                [
                    'code' => 'P001',
                    'name' => 'Producto 1',
                    'cantRequired' => 2,
                    'cantDelivered' => 1,
                ],
                [
                    'code' => 'P002',
                    'name' => 'Producto 2',
                    'cantRequired' => 1,
                    'cantDelivered' => 1,
                ],
                [
                    'code' => 'P003',
                    'name' => 'Producto 3',
                    'cantRequired' => 1,
                    'cantDelivered' => 0,
                ]
            ]
        ];

        return $this->render('components/Delivery/Order.html.twig', [
            'Order' => $order['orderNumber'],
            'CustomerName' => $order['customerName'],
            'CustomerNIT' => $order['customerNIT'],
            'Products' => $order['products'],
        ]);
    }
    
    #[Route('/delivery/close/{orderNumber}', name: 'delivery_close')]
    public function closeOrder ($orderNumber): JsonResponse {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Orden despachada correctamente'
        ]);
    }
    
    #[Route('/delivery/save/{orderNumber}', name: 'delivery_save')]
    public function saveOrder ($orderNumber): JsonResponse {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Orden guardada correctamente'
        ]);
    }

}