<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Service\ContapymeService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct (
        private readonly ContapymeService $contapymeService,
        private readonly ProductService  $productService
    ) {
    }


    /**
     * Getting an order from Contapyme executes the following steps:
     * 1. Set the order as UNPROCESSED in Contapyme.
     * 2. Request the order to Contapyme.
     * 3. Deserialize the order data into an App\Entity\Order object.
     * 4. Validate if the order contains any product to dispatch.
     * 5. With the products list, request the products information (name, barcode, code) to Contapyme and record them
     * in the database.
     * 6. Sets the Delivery object with the order data and the products list.
     */
    #[Route('/order/id={orderNumber}', name: 'getOrder', methods: ['GET'])]
    public function order (Request $request, int $orderNumber): JsonResponse {

        if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
            return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        /*$orderUnprocessed = $this->contapymeService->agentAction(
            actionId: 2,
            orderNumber: $orderNumber
        );*/

        $orderRequest = $this->contapymeService->agentAction(
            actionId: 3,
            orderNumber: $orderNumber
        );

        if (/*$orderUnprocessed['code'] !== Response::HTTP_OK ||*/ $orderRequest['code'] !== Response::HTTP_OK) {
            return new JsonResponse([
                'code' => $orderRequest['code'],
                'message' => $orderRequest['body']
            ]);
        }
        
        $order = new Order($orderRequest['body']['datos']);
        
        if(empty($order->getListaproductos())) {
            return new JsonResponse([
                'code' => Response::HTTP_NO_CONTENT,
                'message' => 'La orden '.$orderNumber.' no tiene productos para despachar.' 
            ], Response::HTTP_NO_CONTENT);
        }
        
        $productsToDispatch = $this->productService->setProductsLists($order->getListaproductos());
        
        $delivery = new Delivery();
        $delivery->setOrderNumber($orderNumber);
        $delivery->setCustomerId($order->getDatosprincipales()->init);
        $delivery->setCreatedAt(new \DateTime());
        $delivery->setTotalRequested(0);
        $delivery->setTotalDispatched(0);
        $delivery->setEfficiency(0);
        $delivery->setProductsList($productsToDispatch);
        

        $jsonResponse = new JsonResponse($delivery->jsonSerialize());
        $jsonResponse->setStatusCode(Response::HTTP_OK);

        return $jsonResponse;
    }

    /**
     * TODO:
     * - Create method for updating the order.
     *  UPDATE: this method will complete the process in Contapyme, and call the method for recording the Delivery in
     * the database.
     * - Create the method for close the order without updating it.
     */

}