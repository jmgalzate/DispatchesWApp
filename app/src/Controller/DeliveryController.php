<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DeliveryService;
use App\Service\ProductService;

class DeliveryController extends AbstractController
{

    public function __construct(private readonly DeliveryService $deliveryService, private readonly RequestStack $requestStack)
    {
    }

    #[Route('/delivery', name: 'home_delivery')]
    public function index(): Response
    {
        return $this->render('components/Delivery.html.twig', [
            'title' => 'Despachar Orden'
        ]);
    }

    /**
     * @throws \JsonException
     */
    #[Route('/delivery/GET/order:{orderNumber}', name: 'delivery')]
    public function delivery(string $orderNumber): JsonResponse
    {
        $order = $this->deliveryService->getOrder($orderNumber);
        $this->requestStack->getSession()->set('actualOrder', $order['body']);
        return new JsonResponse($order['body']);
    }

    #[Route('/delivery/test/{test}', name: 'delivery_status')]
    public function deliveryStatus(string $test): Response
    {
        $order = [
            'orderNumber' => $test,
            'status' => 'delivered'
        ];

        return new Response(
            <<<EOL
            <html lang="es">
                <body>
                    <p>La orden {$order['orderNumber']}: {$order['status']}</p>
                    <a href="/delivery">Regresar a Despachos</a>
                </body>
            </html>
            EOL
        );
    }

    #[Route('/product/GET/barcode:{barcode}', name: 'get_product_by_barcode', methods: ['GET'])]
    public function getProduct(Request $request, ProductService $productService, string $barcode): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse('Recurso no disponible', Response::HTTP_BAD_REQUEST);
        }

        $loadProduct = $productService->vlookupProduct($barcode);
        return new JsonResponse($loadProduct);
    }
}