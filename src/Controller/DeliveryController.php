<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryController extends AbstractController
{

    public function __construct (
        private readonly OrderService $orderService
    ) {
    }

    #[Route('/delivery', name: 'home_delivery')]
    public function index (): Response {
        return $this->render('components/Delivery/Home.html.twig', [
            'title' => 'Despachar Orden'
        ]);
    }

    #[Route('/delivery/{orderNumber}', name: 'delivery_order')]
    public function deliveryOrder ($orderNumber): Response {


        /**
         * TODO:
         * - [] Learn about how to share the Order DTO with all the pages, to avoid deserialize the object here and
         * then work with it as an array.
         * - [] Define how to manage the information with JS in the Twig page for the Delivery Object, 'cause the
         * Session information is not possible to fill it, and the idea is to don't refresh the page each time a new
         * item is scanned.
         * - [] redefine how to handle the responses by using the HTTP:codes provided by Symfony instead of check 
         * each array string.
         */
        
        /** 1. Unprocess the Order */
        /*$actionUnprocess = $this->orderService->unprocess($orderNumber);

        if ($actionUnprocess->getStatusCode() !== Response::HTTP_OK) {
            return new Response(
                '<html lang="es">
                            <body>
                                <script>
                                    alert("La orden no fue des procesada correctamente, por favor validar.");
                                    window.location.href = "/delivery";
                                </script>
                            </body>
                         </html>');
        }*/

        /** 2. Load the Order */
       /* $actionLoad = $this->orderService->load($orderNumber);

        if ($actionLoad->getStatusCode() !== Response::HTTP_OK) {
            return new Response(
                '<html lang="es">
                            <body>
                                <script>
                                    alert("La orden no se cargó correctamente, por favor validar.");
                                    window.location.href = "/delivery";
                                </script>
                            </body>
                         </html>');
        }
        
        $actionLoadContent = $actionLoad->getContent();
        $orderData = json_decode($actionLoadContent, true);*/

        /*return $this->render('components/Delivery/Order.html.twig', [
            'Order' => $orderData['orderNumber'],
            'CustomerName' => $orderData['customerName'],
            'CustomerNIT' => $orderData['customerNIT'],
            'Products' => $orderData['productsToDeliver'],
        ]);*/
        

        $order = [
            'orderNumber' => $orderNumber,
            'customerName' => 'Juan',
            'customerNIT' => '90077886655',
            'products' => [
                [
                    'code' => 'P001',
                    'name' => 'Producto 11111111111111111111111111111111',
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