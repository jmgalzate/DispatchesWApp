<?php

namespace App\Controller;

use App\Entity\Delivery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ProductService;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly ProductService $productService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $delivery = $this->entityManager->getRepository(Delivery::class);
        
        return $this->render('components/Home.html.twig', [
            'title' => 'Bienvenid@ al Portal de Despachos',
            'dispatchButton' => 'Nuevo despacho',
            'totalProducts' => $this->productService->totalProductsInDB(),
            'lastDeliveryRecorded' => $delivery->lastDeliveryRecorded(),
            'totalDispatchesToday' => $delivery->totalDispatchesToday(),
            'totalDispatchesThisMonth' => $delivery->totalDispatchesThisMonth(),
            'avgEfficiencyToday' => $delivery->avgEfficiencyToday(),
            'avgEfficiencyThisMonth' => $delivery->avgEfficiencyThisMonth(),
        ]);
    }
}
