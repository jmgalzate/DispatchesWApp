<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Entity\Order\MainData;
use App\Entity\Order\Header;
use App\Entity\Order\Settlement;
use App\Entity\Order\Product;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'order')]
#[AllowDynamicProperties] class Order
{

  #[ORM\Column(type: 'integer')]
  #[ORM\Id]
  #[ORM\GeneratedValue]
  private ?int $id = null;
  
  #[ORM\Column(name: 'orderNumber', type: 'integer')]
  private ?int $orderNumber = null;

  #[ORM\Column(name: 'header', type: 'TEXT')]
  private ?Header $encabezado;

  #[ORM\Column(name: 'settlement', type: 'TEXT')]
  private ?Settlement $liquidacion;

  #[ORM\Column(name: 'mainData', type: 'TEXT')]
  private ?MainData $datosprincipales;

  /** @var Product[] */
  #[ORM\Column(name: 'productsList', type: 'TEXT')]
  private array $listaproductos;

  #[ORM\Column(name: 'qoprsok', type: 'string', length: 10, nullable: true)]
  private ?string $qoprsok;

  public function getId (): ?int {
    return $this->id;
  }
  
  public function getOrderNumber (): ?int {
    return $this->orderNumber;
  }
  
  public function setOrderNumber (int $orderNumber): self {
    $this->orderNumber = $orderNumber;
    
    return $this;
  }

  public function getEncabezado (): ?Header {
    return $this->encabezado;
  }

  public function setEncabezado (Header $encabezado): self {
    $this->encabezado = $encabezado;

    return $this;
  }

  public function getLiquidacion (): ?Settlement {
    return $this->liquidacion;
  }

  public function setLiquidacion (Settlement $liquidacion): self {

    $this->liquidacion = $liquidacion;

    return $this;
  }

  public function getDatosprincipales (): ?MainData {
    return $this->datosprincipales;
  }

  public function setDatosprincipales (MainData $datosprincipales): self {
    $this->datosprincipales = $datosprincipales;

    return $this;
  }

  /**
   * @return Product[]|null
   */
  public function getListaproductos (): ?array {
    return $this->listaproductos;
  }

  public function setListaproductos (array $listaproductos): self {
    $this->listaproductos = $listaproductos;
    return $this;
  }

  public function getQoprsok (): ?string {
    return $this->qoprsok;
  }

  public function setQoprsok (?string $qoprsok): self {
    $this->qoprsok = $qoprsok;

    return $this;
  }

  public function setIprocess (int $iprocess): self {
    $this->encabezado->iprocess = $iprocess;

    return $this;
  }

  /** This method deserialize the Order object(array) for each attribute object */
  public static function fromArray (int $orderNumber, array $orderData): self {

    $order = new self();
    
    $order->setOrderNumber($orderNumber);

    $header = new Header();
    $header->tdetalle = $orderData['encabezado']['tdetalle'];
    $header->itdoper = $orderData['encabezado']['itdoper'];
    $header->snumsop = $orderData['encabezado']['snumsop'];
    $header->fsoport = $orderData['encabezado']['fsoport'];
    $header->iccbase = $orderData['encabezado']['iccbase'];
    $header->imoneda = $orderData['encabezado']['imoneda'];
    $header->banulada = $orderData['encabezado']['banulada'];
    $header->blocal = $orderData['encabezado']['blocal'];
    $header->bniif = $orderData['encabezado']['bniif'];
    $header->svaloradic1 = $orderData['encabezado']['svaloradic1'];
    $header->svaloradic2 = $orderData['encabezado']['svaloradic2'];
    $header->svaloradic3 = $orderData['encabezado']['svaloradic3'];
    $header->svaloradic4 = $orderData['encabezado']['svaloradic4'];
    $header->svaloradic5 = $orderData['encabezado']['svaloradic5'];
    $header->svaloradic6 = $orderData['encabezado']['svaloradic6'];
    $header->svaloradic7 = $orderData['encabezado']['svaloradic7'];
    $header->svaloradic8 = $orderData['encabezado']['svaloradic8'];
    $header->svaloradic9 = $orderData['encabezado']['svaloradic9'];
    $header->svaloradic10 = $orderData['encabezado']['svaloradic10'];
    $header->svaloradic11 = $orderData['encabezado']['svaloradic11'];
    $header->svaloradic12 = $orderData['encabezado']['svaloradic12'];
    $header->fecha1adic = $orderData['encabezado']['fecha1adic'];
    $header->fecha2adic = $orderData['encabezado']['fecha2adic'];
    $header->fecha3adic = $orderData['encabezado']['fecha3adic'];
    $header->datosaddin = $orderData['encabezado']['datosaddin'];
    $header->fcreacion = $orderData['encabezado']['fcreacion'];
    $header->fultima = $orderData['encabezado']['fultima'];
    $header->fprocesam = $orderData['encabezado']['fprocesam'];
    $header->iusuario = $orderData['encabezado']['iusuario'];
    $header->iusuarioult = $orderData['encabezado']['iusuarioult'];
    $header->isucursal = $orderData['encabezado']['isucursal'];
    $header->inumoperultimp = $orderData['encabezado']['inumoperultimp'];
    $header->accionesalgrabar = $orderData['encabezado']['accionesalgrabar'];
    $header->iemp = $orderData['encabezado']['iemp'];
    $header->inumoper = $orderData['encabezado']['inumoper'];
    $header->itdsop = $orderData['encabezado']['itdsop'];
    $header->inumsop = $orderData['encabezado']['inumsop'];
    $header->iclasifop = $orderData['encabezado']['iclasifop'];
    $header->iprocess = $orderData['encabezado']['iprocess'];
    $header->mtotaloperacion = $orderData['encabezado']['mtotaloperacion'];

    $order->setEncabezado($header);


    $settlement = new Settlement();
    $settlement->parcial = $orderData['liquidacion']['parcial'];
    $settlement->descuento = $orderData['liquidacion']['descuento'];
    $settlement->iva = $orderData['liquidacion']['iva'];
    $settlement->total = $orderData['liquidacion']['total'];

    $order->setLiquidacion($settlement);


    $mainData = new MainData();
    $mainData->init = $orderData['datosprincipales']['init'];
    $mainData->initvendedor = $orderData['datosprincipales']['initvendedor'];
    $mainData->finicio = $orderData['datosprincipales']['finicio'];
    $mainData->sobserv = $orderData['datosprincipales']['sobserv'];
    $mainData->bregvrunit = $orderData['datosprincipales']['bregvrunit'];
    $mainData->bregvrtotal = $orderData['datosprincipales']['bregvrtotal'];
    $mainData->condicion1 = $orderData['datosprincipales']['condicion1'];
    $mainData->icuenta = $orderData['datosprincipales']['icuenta'];
    $mainData->blistaconiva = $orderData['datosprincipales']['blistaconiva'];
    $mainData->icccxp = $orderData['datosprincipales']['icccxp'];
    $mainData->busarotramoneda = $orderData['datosprincipales']['busarotramoneda'];
    $mainData->imonedaimpresion = $orderData['datosprincipales']['imonedaimpresion'];
    $mainData->ireferencia = $orderData['datosprincipales']['ireferencia'];
    $mainData->bcerrarref = $orderData['datosprincipales']['bcerrarref'];
    $mainData->qdias = $orderData['datosprincipales']['qdias'];
    $mainData->iinventario = $orderData['datosprincipales']['iinventario'];
    $mainData->ilistaprecios = $orderData['datosprincipales']['ilistaprecios'];
    $mainData->qporcdescuento = $orderData['datosprincipales']['qporcdescuento'];
    $mainData->frmenvio = $orderData['datosprincipales']['frmenvio'];
    $mainData->frmpago = $orderData['datosprincipales']['frmpago'];
    $mainData->mtasacambio = $orderData['datosprincipales']['mtasacambio'];
    $mainData->qregfcobro = $orderData['datosprincipales']['qregfcobro'];
    $mainData->isucursalcliente = $orderData['datosprincipales']['isucursalcliente'];

    $order->setDatosprincipales($mainData);
    
    
    if(empty($orderData['listaproductos']))
      $order->setListaproductos([]);
    else {
      $listaproductos = [];

      foreach ($orderData['listaproductos'] as $producto) {
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

        $listaproductos[] = $product;
      }

      $order->setListaproductos($listaproductos);
    }
    
    $order->setQoprsok($orderData['qoprsok']);

    return $order;
  }
}
