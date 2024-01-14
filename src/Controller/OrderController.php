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

  #[Route('/order/id={orderNumber}', name: 'getOrder', methods: ['GET'])]
  public function getOrder (Request $request, int $orderNumber): JsonResponse {

    if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    try {
      /** 1. The order is Unprocessed */
      $orderUnprocessed = $this->contapymeService->agentAction(
        actionId: 2,
        orderNumber: $orderNumber
      );

      if ($orderUnprocessed['code'] !== Response::HTTP_OK) {
        throw new \Exception($orderUnprocessed['body']);
      }

      /** 2. The order is requested */
      $orderRequest = $this->contapymeService->agentAction(
        actionId: 3,
        orderNumber: $orderNumber
      );

      if ($orderRequest['code'] !== Response::HTTP_OK) {
        throw new \Exception($orderRequest['body']);
      }
    } catch (\Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => $e->getMessage()
      ]);
    }

    /** 3. The order is deserialized */
    $order = new Order($orderRequest['body']['datos']);

    /** 4. The order is validated */
    if (empty($order->getListaproductos())) {
      return new JsonResponse([
        'code' => Response::HTTP_NO_CONTENT,
        'message' => 'La orden ' . $orderNumber . ' no tiene productos para despachar.'
      ], Response::HTTP_NO_CONTENT);
    }

    /** 5. The products list is requested */
    $productsToDispatch = $this->productService->setProductsLists($order->getListaproductos());

    /** 6. The Delivery object is created and recorded in the Database */
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
    
    /** 7. The Delivery object is serialized and returned */

    $jsonResponse = new JsonResponse($delivery->jsonSerialize());
    $jsonResponse->setStatusCode(Response::HTTP_OK);

    return $jsonResponse;
  }

  #[Route('/order', name: 'updateOrder', methods: ['PUT'])]
  public function updateOrder (Request $request): JsonResponse {

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
     * TODO: Set the new Order object in Contapyme.
     * TODO:Update the Delivery information with the dispatched items.
     */

    $orderSaved = $this->contapymeService->agentAction(
      actionId: 4,
      orderNumber: $orderNumber,
      newOrder: []
    );

    $orderTaxes = $this->contapymeService->agentAction(
      actionId: 5,
      orderNumber: $orderNumber,
      newOrder: []
    );

    $orderProcessed = $this->contapymeService->agentAction(
      actionId: 6,
      orderNumber: $orderNumber
    );

    if ($orderSaved['code'] !== Response::HTTP_OK || $orderTaxes['code'] !== Response::HTTP_OK || $orderProcessed['code'] !== Response::HTTP_OK) {
      return new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => 'something'
      ]);
    }

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