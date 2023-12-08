<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{
    
    public function __construct(
    ) {}
    #[Route('/delivery', name: 'home_delivery')]
    public function index(): Response
    {
        return $this->render('components/Delivery/Home.html.twig', [
            'title' => 'Despachar Orden'
        ]);
    }
    
    #[Route('/delivery/{orderNumber}', name: 'delivery_order')]
    public function deliveryOrder($orderNumber): Response
    {
        return $this->render('components/Delivery/Order.html.twig', [
            'Order' => $orderNumber,
            'CustomerName' => 'Juan',
            'CustomerNIT' => '90077886655',
        ]);
    }

}