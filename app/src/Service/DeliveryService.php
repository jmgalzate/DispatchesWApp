<?php

/**
 * TODO: remove this Service and manage the Methods from DeliveryController
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Order;
use App\Entity\OrderHeader;
use App\Entity\OrderInvoiceSettlement;
use App\Entity\OrderMainData;
use App\Entity\OrderProduct;



class DeliveryService
{
    private ContapymeService $contapymeService;
    private int $nextProductId = 0;

    public function __construct(
        ContapymeService $contapymeService, 
        private readonly RequestStack $requestStack, 
        private readonly LoggerInterface $logger)
    {
        $this->contapymeService = $contapymeService;

    }

    public function loadOrder(string $orderNumber): ?object
    {
        $keyagent = $this->requestStack->getSession()->get('keyagent');
        $order = $this->contapymeService->action(action: 'LOAD', keyagent: $keyagent, order: $orderNumber);
        
        $orderObj = $this->handlingOrder(json_decode($order->getContent()));

        


        return $orderObj;
    }

    private function handlingOrder($order): Order
    {
        $encabezado = new OrderHeader();
            $encabezado->setTdetalle($order->body->encabezado->tdetalle);
            $encabezado->setItdoper($order->body->encabezado->itdoper);
            $encabezado->setSnumsop($order->body->encabezado->snumsop);
            $encabezado->setFsoport($order->body->encabezado->fsoport);
            $encabezado->setIccbase($order->body->encabezado->iccbase);
            $encabezado->setImoneda($order->body->encabezado->imoneda);
            $encabezado->setBanulada($order->body->encabezado->banulada);
            $encabezado->setBlocal($order->body->encabezado->blocal);
            $encabezado->setBniif($order->body->encabezado->bniif);
            $encabezado->setSvaloradic1($order->body->encabezado->svaloradic1);
            $encabezado->setSvaloradic2($order->body->encabezado->svaloradic2);
            $encabezado->setSvaloradic3($order->body->encabezado->svaloradic3);
            $encabezado->setSvaloradic4($order->body->encabezado->svaloradic4);
            $encabezado->setSvaloradic5($order->body->encabezado->svaloradic5);
            $encabezado->setSvaloradic6($order->body->encabezado->svaloradic6);
            $encabezado->setSvaloradic7($order->body->encabezado->svaloradic7);
            $encabezado->setSvaloradic8($order->body->encabezado->svaloradic8);
            $encabezado->setSvaloradic9($order->body->encabezado->svaloradic9);
            $encabezado->setSvaloradic10($order->body->encabezado->svaloradic10);
            $encabezado->setSvaloradic11($order->body->encabezado->svaloradic11);
            $encabezado->setSvaloradic12($order->body->encabezado->svaloradic12);
            $encabezado->setFecha1adic($order->body->encabezado->fecha1adic);
            $encabezado->setFecha2adic($order->body->encabezado->fecha2adic);
            $encabezado->setFecha3adic($order->body->encabezado->fecha3adic);
            $encabezado->setDatosaddin($order->body->encabezado->datosaddin);
            $encabezado->setFcreacion($order->body->encabezado->fcreacion);
            $encabezado->setFultima($order->body->encabezado->fultima);
            $encabezado->setFprocesam($order->body->encabezado->fprocesam);
            $encabezado->setIusuario($order->body->encabezado->iusuario);
            $encabezado->setIusuarioult($order->body->encabezado->iusuarioult);
            $encabezado->setIsucursal($order->body->encabezado->isucursal);
            $encabezado->setInumoperultimp($order->body->encabezado->inumoperultimp);
            $encabezado->setAccionesalgrabar($order->body->encabezado->accionesalgrabar);
            $encabezado->setIemp($order->body->encabezado->iemp);
            $encabezado->setInumoper($order->body->encabezado->inumoper);
            $encabezado->setItdsop($order->body->encabezado->itdsop);
            $encabezado->setInumsop($order->body->encabezado->inumsop);
            $encabezado->setIclasifop($order->body->encabezado->iclasifop);
            $encabezado->setIprocess($order->body->encabezado->iprocess);
            $encabezado->setMtotaloperacion($order->body->encabezado->mtotaloperacion);

        $liquidacion = new OrderInvoiceSettlement();
            $liquidacion->setParcial($order->body->liquidacion->parcial);
            $liquidacion->setDescuento($order->body->liquidacion->descuento);
            $liquidacion->setIva($order->body->liquidacion->iva);
            $liquidacion->setTotal($order->body->liquidacion->total);

        $datosPrincipales = new OrderMainData();
            $datosPrincipales->setInit($order->body->datosprincipales->init);
            $datosPrincipales->setInitvendedor($order->body->datosprincipales->initvendedor);
            $datosPrincipales->setFinicio($order->body->datosprincipales->finicio);
            $datosPrincipales->setSobserv($order->body->datosprincipales->sobserv);
            $datosPrincipales->setBregvrunit($order->body->datosprincipales->bregvrunit);
            $datosPrincipales->setBregvrtotal($order->body->datosprincipales->bregvrtotal);
            $datosPrincipales->setCondicion1($order->body->datosprincipales->condicion1);
            $datosPrincipales->setIcuenta($order->body->datosprincipales->icuenta);
            $datosPrincipales->setBlistaconiva($order->body->datosprincipales->blistaconiva);
            $datosPrincipales->setIcccxp($order->body->datosprincipales->icccxp);
            $datosPrincipales->setBusarotramoneda($order->body->datosprincipales->busarotramoneda);
            $datosPrincipales->setImonedaimpresion($order->body->datosprincipales->imonedaimpresion);
            $datosPrincipales->setIreferencia($order->body->datosprincipales->ireferencia);
            $datosPrincipales->setBcerrarref($order->body->datosprincipales->bcerrarref);
            $datosPrincipales->setQdias($order->body->datosprincipales->qdias);
            $datosPrincipales->setIinventario($order->body->datosprincipales->iinventario);
            $datosPrincipales->setIlistaprecios($order->body->datosprincipales->ilistaprecios);
            $datosPrincipales->setQporcdescuento($order->body->datosprincipales->qporcdescuento);
            $datosPrincipales->setFrmenvio($order->body->datosprincipales->frmenvio);
            $datosPrincipales->setFrmpago($order->body->datosprincipales->frmpago);
            $datosPrincipales->setMtasacambio($order->body->datosprincipales->mtasacambio);
            $datosPrincipales->setQregfcobro($order->body->datosprincipales->qregfcobro);
            $datosPrincipales->setIsucursalcliente($order->body->datosprincipales->isucursalcliente);

        $listaProductos = new ArrayCollection();

            foreach ($order->body->listaproductos as $productData) {
                $orderProduct = new OrderProduct();

                    $orderProduct->setIrecurso($productData->irecurso);
                    $orderProduct->setItiporec($productData->itiporec);
                    $orderProduct->setIcc($productData->icc);
                    $orderProduct->setSobserv($productData->sobserv);
                    $orderProduct->setDato1($productData->dato1);
                    $orderProduct->setDato2($productData->dato2);
                    $orderProduct->setDato3($productData->dato3);
                    $orderProduct->setDato4($productData->dato4);
                    $orderProduct->setDato5($productData->dato5);
                    $orderProduct->setDato6($productData->dato6);
                    $orderProduct->setIinventario($productData->iinventario);
                    $orderProduct->setQrecurso($productData->qrecurso);
                    $orderProduct->setMprecio($productData->mprecio);
                    $orderProduct->setQporcdescuento($productData->qporcdescuento);
                    $orderProduct->setQporciva($productData->qporciva);
                    $orderProduct->setMvrtotal($productData->mvrtotal);
                    $orderProduct->setValor1($productData->valor1);
                    $orderProduct->setValor2($productData->valor2);
                    $orderProduct->setValor3($productData->valor3);
                    $orderProduct->setValor4($productData->valor4);
                    $orderProduct->setQrecurso2($productData->qrecurso2);
                
                $listaProductos->add($orderProduct);
                break;
            }   

        return new Order(
            encabezado: $encabezado,
            liquidacion: $liquidacion,
            datosprincipales: $datosPrincipales,
            listaproductos: $listaProductos,
            qoprsok: $order->body->qoprsok
        );
    }

}