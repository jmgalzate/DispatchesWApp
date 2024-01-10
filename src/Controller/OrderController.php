<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Service\ContapymeService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
  public function __construct (
    private readonly ContapymeService       $contapymeService,
    private readonly ProductService         $productService,
    private readonly EntityManagerInterface $entityManager
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
   * 6. Sets the Delivery object with the order data, and the products list.
   */

  #[Route('/order/id={orderNumber}', name: 'getOrder', methods: ['GET'])]
  public function getOrder (Request $request, int $orderNumber): JsonResponse {

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

    if (empty($order->getListaproductos())) {
      return new JsonResponse([
        'code' => Response::HTTP_NO_CONTENT,
        'message' => 'La orden ' . $orderNumber . ' no tiene productos para despachar.'
      ], Response::HTTP_NO_CONTENT);
    }

    $productsToDispatch = $this->productService->setProductsLists($order->getListaproductos());

    $delivery = (new Delivery())
      ->setOrderNumber($orderNumber)
      ->setCustomerId($order->getDatosprincipales()->init)
      ->setCreatedAt(new \DateTime())
      ->setTotalRequested(0)
      ->setTotalDispatched(0)
      ->setEfficiency(0)
      ->setProductsList($productsToDispatch);


    $deliveryId = $this->entityManager->getRepository(Delivery::class)->saveOrUpdate($delivery);
    $delivery->setId($deliveryId);

    $jsonResponse = new JsonResponse($delivery->jsonSerialize());
    $jsonResponse->setStatusCode(Response::HTTP_OK);

    return $jsonResponse;
  }

  #[Route('/order', name: 'updateOrder', methods: ['PUT'])]
  public function updateOrder (Request $request): JsonResponse {

    if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }
    

    /*$orderSaved = $this->contapymeService->agentAction(
        actionId: 4,
        orderNumber: 123456,
        newOrder: [] 
    );

    $orderTaxes = $this->contapymeService->agentAction(
        actionId: 5,
        orderNumber: 123456,
        newOrder: []
    );

    $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: 123456
    );
    
    if ($orderSaved['code'] !== Response::HTTP_OK || $orderTaxes['code'] !== Response::HTTP_OK || $orderProcessed['code'] !== Response::HTTP_OK) {
        return new JsonResponse([
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'something'
        ]);
    }*/

    $jsonResponse = new JsonResponse([
      'code' => $request->getAcceptableContentTypes(),
      'message' => $request->getContent()
    ]);
    $jsonResponse->setStatusCode(Response::HTTP_OK);

    return $jsonResponse;
  }

  #[Route('/order', name: 'closeOrder', methods: ['POST'])]
  public function closeOrder (Request $request): JsonResponse {

    if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    try {
      $order = json_decode($request->getContent(), true);
      $orderNumber = $order['orderNumber'];
    } catch (\Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_BAD_REQUEST,
        'message' => 'La orden no pudo ser cerrada.'
      ]);
    }

    /**
     * The order is processed in Contapyme.
     */

    $orderProcessed = $this->contapymeService->agentAction(
      actionId: 6,
      orderNumber: $orderNumber
    );

    if ($orderProcessed['code'] !== Response::HTTP_OK) {
      return new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => 'something'
      ]);
    }

    $jsonResponse = new JsonResponse([
      'code' => Response::HTTP_OK,
      'message' => 'La orden ha sido cerrada sin guardar cambios.'
    ]);

    $jsonResponse->setStatusCode(Response::HTTP_OK);

    return $jsonResponse;
  }

}