<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Entity\Order\MainData;
use App\Entity\Order\Header;
use App\Entity\Order\Settlement;
use App\Entity\Order\Product;

#[AllowDynamicProperties] class Order
{
    private ?Header $encabezado;
    private ?Settlement $liquidacion;
    private ?MainData $datosprincipales;
    
    /** @var Product[] */
    private array $listaproductos;
    private ?string $qoprsok;
    public function __construct(array $orderData)
    {
        
        $this->encabezado = new Header();
        $this->liquidacion = new Settlement();
        $this->datosprincipales = new MainData();
        $this->listaproductos = [];
        
        $this->setEncabezado($orderData['encabezado']);
        $this->setLiquidacion($orderData['liquidacion']);
        $this->setDatosprincipales($orderData['datosprincipales']);
        $this->setListaproductos($orderData['listaproductos']);
        $this->setQoprsok($orderData['qoprsok']);
    }
    
    public function getEncabezado(): ?Header
    {
        return $this->encabezado;
    }
    
    public function setEncabezado(array $encabezado): self
    {
        $header = new Header();
        
        $header->tdetalle = $encabezado['tdetalle'];
        $header->itdoper = $encabezado['itdoper'];
        $header->snumsop = $encabezado['snumsop'];
        $header->fsoport = $encabezado['fsoport'];
        $header->iccbase = $encabezado['iccbase'];
        $header->imoneda = $encabezado['imoneda'];
        $header->banulada = $encabezado['banulada'];
        $header->blocal = $encabezado['blocal'];
        $header->bniif = $encabezado['bniif'];
        $header->svaloradic1 = $encabezado['svaloradic1'];
        $header->svaloradic2 = $encabezado['svaloradic2'];
        $header->svaloradic3 = $encabezado['svaloradic3'];
        $header->svaloradic4 = $encabezado['svaloradic4'];
        $header->svaloradic5 = $encabezado['svaloradic5'];
        $header->svaloradic6 = $encabezado['svaloradic6'];
        $header->svaloradic7 = $encabezado['svaloradic7'];
        $header->svaloradic8 = $encabezado['svaloradic8'];
        $header->svaloradic9 = $encabezado['svaloradic9'];
        $header->svaloradic10 = $encabezado['svaloradic10'];
        $header->svaloradic11 = $encabezado['svaloradic11'];
        $header->svaloradic12 = $encabezado['svaloradic12'];
        $header->fecha1adic = $encabezado['fecha1adic'];
        $header->fecha2adic = $encabezado['fecha2adic'];
        $header->fecha3adic = $encabezado['fecha3adic'];
        $header->datosaddin = $encabezado['datosaddin'];
        $header->fcreacion = $encabezado['fcreacion'];
        $header->fultima = $encabezado['fultima'];
        $header->fprocesam = $encabezado['fprocesam'];
        $header->iusuario = $encabezado['iusuario'];
        $header->iusuarioult = $encabezado['iusuarioult'];
        $header->isucursal = $encabezado['isucursal'];
        $header->inumoperultimp = $encabezado['inumoperultimp'];
        $header->accionesalgrabar = $encabezado['accionesalgrabar'];
        $header->iemp = $encabezado['iemp'];
        $header->inumoper = $encabezado['inumoper'];
        $header->itdsop = $encabezado['itdsop'];
        $header->inumsop = $encabezado['inumsop'];
        $header->iclasifop = $encabezado['iclasifop'];
        $header->iprocess = $encabezado['iprocess'];
        $header->mtotaloperacion = $encabezado['mtotaloperacion'];
        
        
        $this->encabezado = $header;
        
        return $this;
    }
    
    public function getLiquidacion(): ?Settlement
    {
        return $this->liquidacion;
    }
    
    public function setLiquidacion(array $liquidacion): self
    {
        $settlement = new Settlement();
        $settlement->parcial = $liquidacion['parcial'];
        $settlement->descuento = $liquidacion['descuento'];
        $settlement->iva = $liquidacion['iva'];
        $settlement->total = $liquidacion['total'];
        
        $this->liquidacion = $settlement;
        
        return $this;
    }
    
    public function getDatosprincipales(): ?MainData
    {
        return $this->datosprincipales;
    }
    
    public function setDatosprincipales(array $datosprincipales): self
    {
        $mainData = new MainData();
        
        $mainData->init = $datosprincipales['init'];
        $mainData->initvendedor = $datosprincipales['initvendedor'];
        $mainData->finicio = $datosprincipales['finicio'];
        $mainData->sobserv = $datosprincipales['sobserv'];
        $mainData->bregvrunit = $datosprincipales['bregvrunit'];
        $mainData->bregvrtotal = $datosprincipales['bregvrtotal'];
        $mainData->condicion1 = $datosprincipales['condicion1'];
        $mainData->icuenta = $datosprincipales['icuenta'];
        $mainData->blistaconiva = $datosprincipales['blistaconiva'];
        $mainData->icccxp = $datosprincipales['icccxp'];
        $mainData->busarotramoneda = $datosprincipales['busarotramoneda'];
        $mainData->imonedaimpresion = $datosprincipales['imonedaimpresion'];
        $mainData->ireferencia = $datosprincipales['ireferencia'];
        $mainData->bcerrarref = $datosprincipales['bcerrarref'];
        $mainData->qdias = $datosprincipales['qdias'];
        $mainData->iinventario = $datosprincipales['iinventario'];
        $mainData->ilistaprecios = $datosprincipales['ilistaprecios'];
        $mainData->qporcdescuento = $datosprincipales['qporcdescuento'];
        $mainData->frmenvio = $datosprincipales['frmenvio'];
        $mainData->frmpago = $datosprincipales['frmpago'];
        $mainData->mtasacambio = $datosprincipales['mtasacambio'];
        $mainData->qregfcobro = $datosprincipales['qregfcobro'];
        $mainData->isucursalcliente = $datosprincipales['isucursalcliente'];
        
        
        $this->datosprincipales = $mainData;
        
        return $this;
    }

    /**
     * @return Product[]|null
     */
    public function getListaproductos(): ?array
    {
        return $this->listaproductos;
    }

    public function setListaproductos(array $listaproductos): self
    {
        $products = [];

        foreach ($listaproductos as $producto) {
            $product = new Product();

            $product->irecurso = $producto['irecurso'];
            $product->itiporec = $producto['itiporec'];
            $product->icc = $producto['icc'];
            $product->sobserv = $producto['sobserv'];
            $product->dato1 = $producto['dato1'];
            $product->dato2 = $producto['dato2'];
            $product->dato3 = $producto['dato3'];
            $product->dato4 = $producto['dato4'];
            $product->dato5 = $producto['dato5'];
            $product->dato6 = $producto['dato6'];
            $product->iinventario = $producto['iinventario'];
            $product->qrecurso = intval($producto['qrecurso']);
            $product->mprecio = floatval($producto['mprecio']);
            $product->qporcdescuento = floatval($producto['qporcdescuento']);
            $product->qporciva = $producto['qporciva'];
            $product->mvrtotal = floatval($producto['mvrtotal']);
            $product->valor1 = $producto['valor1'];
            $product->valor2 = $producto['valor2'];
            $product->valor3 = $producto['valor3'];
            $product->valor4 = $producto['valor4'];
            $product->qrecurso2 = $producto['qrecurso2'];

            $products[] = $product;
        }

        $this->listaproductos = $products;

        return $this;
    }
    
    public function getQoprsok(): ?string
    {
        return $this->qoprsok;
    }
    
    public function setQoprsok(?string $qoprsok): self
    {
        $this->qoprsok = $qoprsok;
        
        return $this;
    }
    
    public function setIprocess (int $iprocess): self {
        $this->encabezado->iprocess = $iprocess;
        
        return $this;
    }
}
