<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Service\ContapymeService;
use App\Service\ProductService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

  #[Route('api/get/order={orderNumber}', name: 'getOrder', methods: ['GET'])]
  public function getOrder (Request $request, int $orderNumber): JsonResponse {

    if (!$request->headers->has('Accept') || $request->headers->get('Accept') !== 'application/json') {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    $unprocess = function ($orderNumber) {
      $orderUnprocessed = $this->contapymeService->agentAction(
        actionId: 2,
        orderNumber: $orderNumber
      );

      if ($orderUnprocessed['code'] !== Response::HTTP_OK)
        throw new Exception($orderUnprocessed['body']);
    };

    try {

      /**
       * 7. Check if the delivery is already in the database and if it is dispatched.
       */

      $deliveryRepository = $this->entityManager->getRepository(Delivery::class);
      $delivery = $deliveryRepository->findOneBy(['orderNumber' => $orderNumber]);

      if ($delivery) {

        if ($delivery->getIsDispatched() === false) {

          $unprocess($orderNumber);

          $jsonResponse = new JsonResponse($delivery->jsonSerialize());
          $jsonResponse->setStatusCode(Response::HTTP_OK);
          return $jsonResponse;
        } else {
          $jsonResponse = new JsonResponse([
            'code' => Response::HTTP_ALREADY_REPORTED,
            'message' => 'La orden ya fue despachada.' //TODO: create arrow function for process again the order. 
          ]);

          $jsonResponse->setStatusCode(Response::HTTP_ALREADY_REPORTED);
          return $jsonResponse;
        }
      } else {
        /** 1. Try to get the order */
        $orderRequest = $this->contapymeService->agentAction(
          actionId: 3,
          orderNumber: $orderNumber
        );

        if ($orderRequest['code'] !== Response::HTTP_OK)
          throw new Exception($orderRequest['body']);

        /** 2. Deserialize the order*/
        $order = Order::fromArray(orderNumber: $orderNumber, orderData: $orderRequest['body']['datos']);

        /** 3. Validate if there are products in the Order*/
        if (empty($order->getListaproductos()))
          throw new Exception('La orden ' . $orderNumber . ' no tiene productos para despachar.');


        /** 4. Check if the order is processed to unprocess it */
        if ($order->getEncabezado()->iprocess === 0) {

          /** 5. Unprocess the order */
          $orderUnprocessed = $this->contapymeService->agentAction(
            actionId: 2,
            orderNumber: $orderNumber
          );

          if ($orderUnprocessed['code'] !== Response::HTTP_OK)
            throw new Exception($orderUnprocessed['body']);

          $order->setIprocess(2); // 2 = Unprocessed
        }

        /** 6. The Order is recorded in the DB */
        $this->entityManager->getRepository(Order::class)->save($order);
      }
    } catch (Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => $e->getMessage()
      ]);
    }


    /** 8. Set the products to Dispatch */
    $productsToDispatch = $this->productService->setProductsLists($order->getListaproductos());

    $totalRequested = 0;

    foreach ($productsToDispatch as $product) {
      $totalRequested += $product->getRequestedQuantity();
    }

    /** 9. Set and record the Delivery request */
    $delivery = (new Delivery())
      ->setOrderNumber($orderNumber)
      ->setCustomerId($order->getDatosprincipales()->init)
      ->setCreatedAt(new DateTime())
      ->setTotalRequested($totalRequested)
      ->setTotalDispatched(0)
      ->setEfficiency(0)
      ->setProductsList($productsToDispatch)
      ->setIsDispatched(false);

    $delivery = $this->entityManager->getRepository(Delivery::class)->save($delivery, true);

    /** 10. The Delivery object is serialized and returned */

    $jsonResponse = new JsonResponse($delivery->jsonSerialize());
    $jsonResponse->setStatusCode(Response::HTTP_OK);

    return $jsonResponse;
  }

  /**
   * This method will receive the order that must be updated in Contapyme and the Delivery object to update the efficiency and the dispatched products.
   */
  #[Route('api/update/delivery', name: 'updateOrder', methods: ['PUT'])]
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
        ->setCreatedAt(new DateTime())
        ->setTotalRequested($deliveryData['totalRequested'])
        ->setTotalDispatched($deliveryData['totalDispatched'])
        ->setEfficiency($deliveryData['efficiency'])
        ->setProductsList($deliveryData['productsList'])
        ->setIsDispatched(true);

      // $this->entityManager->getRepository(Delivery::class)->save($delivery, true);

      /** Getting the Order from the Database */
      $order = $this->entityManager->getRepository(Order::class)->findOneBy(['orderNumber' => $delivery->getOrderNumber()]);

      /** Updating some data in the Order object */
      $order->setLiquidacion(new Order\Settlement());
      $order->setIusuarioult("WEBAPI");

      /** Set the new products list  */
      $productsList = [];

      foreach ($order->getListaproductos() as $requestedProduct) {
        foreach ($delivery->getProductsList() as $dispatchedProduct) {

          if ($requestedProduct['irecurso'] === $dispatchedProduct['code']) {

            if ($dispatchedProduct['deliveredQuantity'] !== 0) {
              $requestedProduct['qrecurso'] = $dispatchedProduct['deliveredQuantity'];

              $newPrice = $requestedProduct['qrecurso'] * $requestedProduct['mprecio'];
              $discount = $requestedProduct['qporcdescuento'] / 100;

              $requestedProduct['mvrtotal'] = ($newPrice - ($newPrice * $discount));
  
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
        throw new Exception('Falló el intento de "Guardar" la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');

      $orderTaxes = $this->contapymeService->agentAction(
        actionId: 5,
        orderNumber: $delivery->getOrderNumber(),
        newOrder: $order->jsonSerialize()
      );

      if ($orderTaxes['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Calcular los Impuestos" de la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');
      
      /** 2. Try to process the Order */
      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $delivery->getOrderNumber()
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      /** 3. Confirm the Order is closed in this API without changes in Contapyme. */
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_OK,
        'message' => 'La orden ha sido guardada en Contapyme y procesada correctamente.'
      ]);

      $jsonResponse->setStatusCode(Response::HTTP_OK);

      return $jsonResponse;
    } catch (Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_NOT_MODIFIED,
        'message' => $e->getMessage()
      ]);
    }
  }

  /**
   * This method will receive the order that must not be updated in Contapyme but the Delivery object is updated.
   */
  #[Route('api/dispatch/delivery', name: 'closeAndDispatch', methods: ['PUT'])]
  public function closeAndDispatch (Request $request): JsonResponse {
    try {
      /** Setting the received Delivery */

      $deliveryData = json_decode($request->getContent(), true);
      $delivery = (new Delivery())
        ->setId($deliveryData['id'])
        ->setOrderNumber($deliveryData['orderNumber'])
        ->setCustomerId($deliveryData['customerId'])
        ->setCreatedAt(new DateTime())
        ->setTotalRequested($deliveryData['totalRequested'])
        ->setTotalDispatched($deliveryData['totalDispatched'])
        ->setEfficiency($deliveryData['efficiency'])
        ->setProductsList($deliveryData['productsList'])
        ->setIsDispatched(true);

      $this->entityManager->getRepository(Delivery::class)->update($delivery);


      /** 2. Try to process the Order */
      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $deliveryData['orderNumber']
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      /** 3. Confirm the Order is closed in this API without changes in Contapyme. */
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_OK,
        'message' => 'El despacho ha sido registrado sin guardar los cambios en Contapyme.'
      ]);

    } catch (Exception $e) {
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => $e->getMessage()
      ]);
    }

    return $jsonResponse;
  }

  /**
   * This method will receive the order that must not be updated in Contapyme and the Dispatch is set to false, as the order is not dispatched.
   */
  #[Route('api/cancel/delivery', name: 'closeWithoutDispatch', methods: ['DELETE'])]
  public function closeWithoutDispatch (Request $request): JsonResponse {

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
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      /** 3. Confirm the Order is closed in this API without changes in Contapyme. */
      $jsonResponse = new JsonResponse([
        'code' => Response::HTTP_OK,
        'message' => 'El despacho se ha cancelado y la orden se cerró en Contapyme sin guardar cambios.'
      ]);

      $jsonResponse->setStatusCode(Response::HTTP_OK);

      return $jsonResponse;
    } catch (Exception $e) {
      return new JsonResponse([
        'code' => Response::HTTP_NOT_MODIFIED,
        'message' => $e->getMessage()
      ]);
    }
  }
}