<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Entity\Order;
use App\Service\ContapymeService;
use App\Service\ProductService;
use App\Entity\Log;
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
  private $loggerRepository;

  public function __construct (
    private ContapymeService $contapymeService,
    private ProductService $productService,
    private EntityManagerInterface $entityManager
  ) {
    $this->loggerRepository = $this->entityManager->getRepository(Log::class);
  }

  #[Route('api/get/order={orderNumber}', name: 'getOrder', methods: ['GET'])]
  public function getOrder (Request $request, int $orderNumber): JsonResponse {
    if (!$this->isRequestAuthorized($request)) {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    try {
      $delivery = $this->getDelivery($orderNumber);

      if ($delivery) {
        return $this->handleExistingDelivery($delivery, $orderNumber);
      } else {
        return $this->handleNewDelivery($orderNumber);
      }
      
    } catch (Exception $e) {
      return $this->handleException($e, 'LOAD', $orderNumber);
    }
  }

  #[Route('api/update/delivery', name: 'updateOrder', methods: ['PUT'])]
  public function updateOrder (Request $request): JsonResponse {
    if (!$this->isRequestAuthorized($request)) {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    $deliveryData = json_decode($request->getContent(), true);

    $this->saveLog(
      logType: 2,
      action: 'SAVE',
      details:'Inicia proceso de actualización de la orden ' . $deliveryData['orderNumber'] . ' en Contapyme.');
    
    try {
      
      $delivery = $this->getDelivery($deliveryData['orderNumber']);
      $delivery->setTotalDispatched($deliveryData['totalDispatched']);
      $delivery->setEfficiency($deliveryData['efficiency']);
      $delivery->setProductsList($deliveryData['productsList']);
      $delivery->setIsDispatched(true);
      $this->updateDelivery($delivery);
      
      $order = $this->getOrderFromDatabase($deliveryData['orderNumber']);
      
      $orderUpdated = $this->updateOrderInDatabase($order, $delivery);

      $orderSaved = $this->contapymeService->agentAction(
        actionId: 4,
        orderNumber: $delivery->getOrderNumber(),
        newOrder: $orderUpdated->jsonSerialize()
      );

      if ($orderSaved['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Guardar" la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');

      $orderTaxes = $this->contapymeService->agentAction(
        actionId: 5,
        orderNumber: $delivery->getOrderNumber(),
        newOrder: $orderUpdated->jsonSerialize()
      );

      if ($orderTaxes['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Calcular los Impuestos" de la orden en Contapyme; por favor valide los logs para obtener más detalles de la transacción.');
      
      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $delivery->getOrderNumber()
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      $this->saveLog(
        logType: 2,
        action: 'SAVE',
        details:'El proceso de actualización de la orden ' . $deliveryData['orderNumber'] . ' fue exitoso.');

      return $this->createJsonResponse('La orden ha sido guardada en Contapyme y procesada correctamente.', Response::HTTP_OK);
    } catch (Exception $e) {

      $this->saveLog(
        logType: 0,
        action: 'SAVE',
        details:'Falló la actualización de la orden ' . $deliveryData['orderNumber'] . ' en Contapyme. ' . $e->getMessage());
      
      return $this->handleException($e, 'SAVE', $deliveryData['orderNumber']);
    }
  }

  #[Route('api/dispatch/delivery', name: 'closeAndDispatch', methods: ['PUT'])]
  public function closeAndDispatch (Request $request): JsonResponse {
    if (!$this->isRequestAuthorized($request)) {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    $deliveryData = json_decode($request->getContent(), true);

    $this->saveLog(
      logType: 2,
      action: 'SAVE',
      details:'Inicia proceso de cierre de la orden ' . $deliveryData['orderNumber'] . ' en Contapyme sin actualizaciones.');

    try {

      $delivery = $this->getDelivery($deliveryData['orderNumber']);
      $delivery->setTotalDispatched($deliveryData['totalDispatched']);
      $delivery->setEfficiency($deliveryData['efficiency']);
      $delivery->setProductsList($deliveryData['productsList']);
      $delivery->setIsDispatched(true);
      $this->updateDelivery($delivery);

      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $delivery->getOrderNumber()
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      $this->saveLog(
        logType: 2,
        action: 'SAVE',
        details:'Finaliza proceso de cierre de la orden ' . $deliveryData['orderNumber'] . ' en Contapyme sin actualizaciones con éxito.');

      return $this->createJsonResponse('La orden ha sido procesada en Contapyme.', 
        Response::HTTP_OK);
    } catch (Exception $e) {

      $this->saveLog(
        logType: 0,
        action: 'SAVE',
        details:'Falló el proceso de cierre de la orden ' . $deliveryData['orderNumber'] . ' en Contapyme. '. $e->getMessage());
      
      return $this->handleException($e, 'DISPATCH', $deliveryData['orderNumber']);
    }
  }

  #[Route('api/cancel/delivery', name: 'closeWithoutDispatch', methods: ['DELETE'])]
  public function closeWithoutDispatch (Request $request): JsonResponse {
    if (!$this->isRequestAuthorized($request)) {
      return new JsonResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    $deliveryData = json_decode($request->getContent(), true);

    $this->saveLog(
      logType: 2,
      action: 'CANCEL',
      details:'Cerrando la orden ' . $deliveryData['orderNumber'] . ' sin modificaciones.');

    try {

      $orderProcessed = $this->contapymeService->agentAction(
        actionId: 6,
        orderNumber: $deliveryData['orderNumber']
      );

      if ($orderProcessed['code'] !== Response::HTTP_OK)
        throw new Exception('Falló el intento de "Procesar" la orden en Contapyme; por favor verifique y procésela manualmente.');

      $this->saveLog(
        logType: 2,
        action: 'CANCEL',
        details:'Cerrada la orden ' . $deliveryData['orderNumber'] . ' sin modificaciones.');

      return $this->createJsonResponse('La orden ha sido cerrada sin modificaciones', Response::HTTP_OK);
    } catch (Exception $e) {

      $this->saveLog(
        logType: 0,
        action: 'CANCEL',
        details:'Hubo un problema al intentar cerrar la orden ' . $deliveryData['orderNumber'] . ' en Contapyme.');
      
      return $this->handleException($e, 'CANCEL', $deliveryData['orderNumber']);
    }
  }
  
  
  private function isRequestAuthorized(Request $request): bool {
    return $request->headers->has('Accept') && $request->headers->get('Accept') === 'application/json';
  }

  private function getDelivery(int $orderNumber): ?Delivery {
    $deliveryRepository = $this->entityManager->getRepository(Delivery::class);
    return $deliveryRepository->findOneBy(['orderNumber' => $orderNumber]);
  }

  private function handleExistingDelivery(Delivery $delivery, int $orderNumber): JsonResponse {
    
    if($delivery->getIsDispatched()){
      $this->saveLog(
        logType: 1,
        action: 'LOAD',
        details:'La orden ' . $orderNumber . ' ya ha sido cargada por lo que no se creará un nuevo despacho.');
      
      return $this->createJsonResponse('La orden ' . $orderNumber . ' ya ha sido despachada.', Response::HTTP_ALREADY_REPORTED);
    } else {

      $order = $this->getOrderFromContapyme($orderNumber);

      if(isset($order['Error'])){
        return $this->createJsonResponse($order['Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
      }
      
      $this->saveLog(
        logType: 1,
        action: 'LOAD',
        details:'La orden ' . $orderNumber . ' ya se había descargada pero no se había despachado, por lo que se carga para ser despachada.');
      
      return $this->createJsonResponse($delivery->jsonSerialize(), Response::HTTP_OK);
    }
  }

  /**
   * @throws Exception
   */
  private function handleNewDelivery(int $orderNumber): JsonResponse {
    
    $order = $this->getOrderFromContapyme($orderNumber);
    
    if($order['Error']){
      return $this->createJsonResponse($order['Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    
    $productsToDispatch = $this->productService->setProductsLists($order['Order']->getListaproductos());

    $totalRequested = 0;

    foreach ($productsToDispatch as $product) {
      $totalRequested += $product->getRequestedQuantity();
    }
    
    $delivery = (new Delivery())
      ->setOrderNumber($orderNumber)
      ->setCustomerId($order['Order']->getDatosprincipales()->init)
      ->setCreatedAt(new DateTime())
      ->setTotalRequested($totalRequested)
      ->setTotalDispatched(0)
      ->setEfficiency(0)
      ->setProductsList($productsToDispatch)
      ->setIsDispatched(false);

    $this->updateDelivery($delivery);
    
    return $this->createJsonResponse($delivery->jsonSerialize(), Response::HTTP_OK);
    
  }

  private function handleException(Exception $e, string $action, int $orderNumber): JsonResponse {
    $this->saveLog(0, $action, 'An error happened while trying to '. $action .' the order ' . $orderNumber . '. ' . 
      $e->getMessage());
    return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  private function updateDelivery($deliveryData): void{
    $this->entityManager->getRepository(Delivery::class)->save($deliveryData, true);
  }
  
  private function getOrderFromContapyme(int $orderNumber): array {

    $this->saveLog(
      logType: 2,
      action: 'LOAD',
      details:'Descargando la orden ' . $orderNumber . ' de Contapyme.');
    
    try{

      $orderRequest = $this->contapymeService->agentAction(
        actionId: 3,
        orderNumber: $orderNumber
      );

      if ($orderRequest['code'] !== Response::HTTP_OK)
        throw new Exception($orderRequest['body']);

      $order = Order::fromArray(orderNumber: $orderNumber, orderData: $orderRequest['body']['datos']);

      if (empty($order->getListaproductos()))
        throw new Exception('La orden ' . $orderNumber . ' no tiene productos para despachar.');

      if ($order->getEncabezado()->iprocess === 0) {

        $orderUnprocessed = $this->contapymeService->agentAction(
          actionId: 2,
          orderNumber: $orderNumber
        );

        if ($orderUnprocessed['code'] !== Response::HTTP_OK)
          throw new Exception($orderUnprocessed['body']);

        $order->setIprocess(2); // 2 = Unprocessed
      }

      $this->entityManager->getRepository(Order::class)->save($order);

      $this->saveLog(
        logType: 2,
        action: 'LOAD',
        details:'Orden ' . $orderNumber . ' cargada correctamente.');
      
      return ['Order' => $order];

    } catch (Exception $e) {

      $this->saveLog(
        logType: 0,
        action: 'LOAD',
        details:'Hubo un error cargando la orden ' . $orderNumber . '. ' . $e->getMessage());
      
      return ['Error' => $this->handleException($e, 'LOAD', $orderNumber)];
    }
  }

  private function getOrderFromDatabase(int $orderNumber): Order {
    return $this->entityManager->getRepository(Order::class)->findOneBy(['orderNumber' => $orderNumber]);
  }

  private function updateOrderInDatabase(Order $order, Delivery $delivery): Order {

    $order->setLiquidacion(new Order\Settlement());
    $order->setIusuarioult("WEBAPI");

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

    $this->entityManager->getRepository(Order::class)->update($order);
    return $order;
  }

  private function createJsonResponse($message, int $statusCode): JsonResponse {
    $jsonResponse = new JsonResponse($message);
    $jsonResponse->setStatusCode($statusCode);
    return $jsonResponse;
  }

  private function saveLog(int $logType, string $action, string $details): void {
    $this->loggerRepository->save(
      (new Log())
        ->setLogType($logType)
        ->setLogDetails(json_encode([
          'action' => $action,
          'details' => $details
        ]))
        ->setCreatedAt(new \DateTime()),
      true
    );
  }
}