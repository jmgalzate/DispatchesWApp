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

      /** 1. Try to get the order */
      $orderRequest = $this->contapymeService->agentAction(
        actionId: 3,
        orderNumber: $orderNumber
      );

      if ($orderRequest['code'] !== Response::HTTP_OK)
        throw new \Exception($orderRequest['body']);

      /** 2. Deserialize the order*/
      $order = Order::fromArray(orderNumber: $orderNumber, orderData: $orderRequest['body']['datos']);

      /** 3. Validate if there are products in the Order*/
      if (empty($order->getListaproductos()))
        throw new \Exception('La orden ' . $orderNumber . ' no tiene productos para despachar.');


      /** 4. Check if the order is processed to unprocess it */
      if ($order->getEncabezado()->iprocess === 0) {

        /** 5. Unprocess the order */
        $orderUnprocessed = $this->contapymeService->agentAction(
          actionId: 2,
          orderNumber: $orderNumber
        );

        if ($orderUnprocessed['code'] !== Response::HTTP_OK)
          throw new \Exception($orderUnprocessed['body']);

        $order->setIprocess(2); // 2 = Unprocessed
      }

      /** 6. The Order is recorded in the DB */
      $this->entityManager->getRepository(Order::class)->save($order);

    } catch (\Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => $e->getMessage()
      ]);
    }

    /** 7. Set the products to Dispatch */
    $productsToDispatch = $this->productService->setProductsLists($order->getListaproductos());
    
    $totalRequested = 0;
    
    foreach($productsToDispatch as $product) {
      $totalRequested += $product->getRequestedQuantity();
    }

    /** 8. Set and record the Delivery request */
    $delivery = (new Delivery())
      ->setOrderNumber($orderNumber)
      ->setCustomerId($order->getDatosprincipales()->init)
      ->setCreatedAt(new \DateTime())
      ->setTotalRequested($totalRequested)
      ->setTotalDispatched(0)
      ->setEfficiency(0)
      ->setProductsList($productsToDispatch);

    $delivery = $this->entityManager->getRepository(Delivery::class)->save($delivery, true);

    /** 9. The Delivery object is serialized and returned */

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

      /** Setting the received Delivery */

      $deliveryData = json_decode($request->getContent(), true);
      $delivery = (new Delivery())
        ->setId($deliveryData['id'])
        ->setOrderNumber($deliveryData['orderNumber'])
        ->setCustomerId($deliveryData['customerId'])
        ->setCreatedAt(new \DateTime())
        ->setTotalRequested($deliveryData['totalRequested'])
        ->setTotalDispatched($deliveryData['totalDispatched'])
        ->setEfficiency($deliveryData['efficiency'])
        ->setProductsList($deliveryData['productsList']);
      
      $this->entityManager->getRepository(Delivery::class)->update($delivery);


      /** Getting the Order from the Database */
      $order = $this->entityManager->getRepository(Order::class)->findOneBy(['orderNumber' => $delivery->getOrderNumber()]);

      /** Updating some data in the Order object */
      $order->setLiquidacion(new Order\Settlement());
      $order->setIusuarioult("WEBAPI");

      /** Set the new products list  */
      $productsList = [];

      foreach ($order->getListaproductos() as $requestedProduct) {

        foreach ($delivery->getProductsList() as $dispatchedProduct) {
          if ($requestedProduct->irecurso === $dispatchedProduct->getCode()) {

            if ($dispatchedProduct->getDeliveredQuantity() !== 0) {
              $requestedProduct->qrecurso = $dispatchedProduct->getDeliveredQuantity();

              $newPrice = $requestedProduct->qrecurso * $requestedProduct->mprecio;
              $discount = $requestedProduct->qporcdescuento / 100;

              $requestedProduct->mvrtotal = ($newPrice - ($newPrice * $discount));

              $productsList[] = $requestedProduct;
            }

          }
        }
      }

      $order->setListaproductos($productsList);
      
      /** Update the Order in the database */
      $this->entityManager->getRepository(Order::class)->update($order);
      

      $orderSaved = $this->contapymeService->agentAction(
        actionId: 4,
        orderNumber: $delivery->getOrderNumber(),
        newOrder: $order->jsonSerialize()
      );

      if ($orderSaved['code'] !== Response::HTTP_OK)
        throw new \Exception('Falló el intento de "Guardar" la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');

      $orderTaxes = $this->contapymeService->agentAction(
        actionId: 5,
        orderNumber: $delivery->getOrderNumber(),
        newOrder: $order->jsonSerialize()
      );

      if ($orderTaxes['code'] !== Response::HTTP_OK)
        throw new \Exception('Falló el intento de "Calcular los Impuestos" de la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');


      /** 2. Try to process the Order */
      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $delivery->getOrderNumber()
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new \Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      /** 3. Confirm the Order is closed in this API without changes in Contapyme. */
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_OK,
        'message' => 'La orden ha sido guardada en Contapyme y procesada correctamente.'
      ]);

      $jsonResponse->setStatusCode(Response::HTTP_OK);

      return $jsonResponse;

    } catch (\Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_NOT_MODIFIED,
        'message' => $e->getMessage()
      ]);
    }
  }

  #[Route('/order', name: 'closeOrder', methods: ['POST'])]
  public function closeOrder (Request $request): JsonResponse {

    if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    try {

      /** 1. Get the order number */
      $order = json_decode($request->getContent(), true);
      $orderNumber = $order['orderNumber'];

      /** 2. Try to process the Order */
      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $orderNumber
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new \Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      /** 3. Confirm the Order is closed in this API without changes in Contapyme. */
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_OK,
        'message' => 'La orden ha sido cerrada sin guardar cambios.'
      ]);

      $jsonResponse->setStatusCode(Response::HTTP_OK);

      return $jsonResponse;

    } catch (\Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_NOT_MODIFIED,
        'message' => $e->getMessage()
      ]);
    }
  }
}