<?php

namespace App\Controller;

use App\Entity\Contapyme\Order;
use App\Service\DeliveryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{

    public Order $requestedOrder;
    public Order $dispatchedOrder;

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
        $orderData = $this->deliveryService->loadOrder($orderNumber);

        /**
         * TODO: the Order is not properly serialized into a JSON Object for showing it in the view.
         */

        $orderJson = json_encode($orderData, JSON_UNESCAPED_UNICODE);
        $order = json_decode($orderJson);

        //dump($orderData);
        $this->requestedOrder = new Order (
            encabezado: $orderData->getEncabezado(),
            liquidacion: $orderData->getLiquidacion(),
            datosprincipales: $orderData->getDatosprincipales(),
            listaproductos: $orderData->getListaproductos(),
            qoprsok: $orderData->getQoprsok()
        );

        // dispatchedOrder configured with the same data as the requested order and with empty liquidation and Products.
        $this->dispatchedOrder = new Order(
            encabezado: $orderData->getEncabezado(),
            liquidacion: $orderData->getLiquidacion(),
            datosprincipales: $orderData->getDatosprincipales(),
            listaproductos: null,
            qoprsok: $orderData->getQoprsok()
        );

        return new JsonResponse($order);
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