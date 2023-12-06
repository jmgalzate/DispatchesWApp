<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    
    #[Route('/order', name: 'order')]
    public function index(): Response
    {
        return $this->render('components/DeliveryOrder.html.twig', [
            'title' => '12345',
            'CustomerName' => 'Juan',
            'CustomerNIT' => '90077886655',
        ]);
    }

}