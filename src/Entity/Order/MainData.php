<?php

namespace App\Entity\Order;
use Symfony\Component\Serializer\Annotation\SerializedName;

class MainData
{
    /**
     * @SerializedName("init")
     */
    public ?string $init;
    /**
     * @SerializedName("initvendedor")
     */
    public ?string $initvendedor;
    /**
     * @SerializedName("finicio")
     */
    public ?string $finicio;
    /**
     * @SerializedName("sobserv")
     */
    public ?string $sobserv;
    /**
     * @SerializedName("bregvrunit")
     */
    public ?string $bregvrunit;
    /**
     * @SerializedName("bregvrtotal")
     */
    public ?string $bregvrtotal;
    /**
     * @SerializedName("condicion1")
     */
    public ?string $condicion1;
    /**
     * @SerializedName("icuenta")
     */
    public ?string $icuenta;
    /**
     * @SerializedName("blistaconiva")
     */
    public ?string $blistaconiva;
    /**
     * @SerializedName("icccxp")
     */
    public ?string $icccxp;
    /**
     * @SerializedName("busarotramoneda")
     */
    public ?string $busarotramoneda;
    /**
     * @SerializedName("imonedaimpresion")
     */
    public ?string $imonedaimpresion;
    /**
     * @SerializedName("ireferencia")
     */
    public ?string $ireferencia;
    /**
     * @SerializedName("bcerrarref")
     */
    public ?string $bcerrarref;
    /**
     * @SerializedName("qdias")
     */
    public ?string $qdias;
    /**
     * @SerializedName("iinventario")
     */
    public ?string $iinventario;
    /**
     * @SerializedName("ilistaprecios")
     */
    public ?string $ilistaprecios;
    /**
     * @SerializedName("qporcdescuento")
     */
    public ?string $qporcdescuento;
    /**
     * @SerializedName("frmenvio")
     */
    public ?string $frmenvio;
    /**
     * @SerializedName("frmpago")
     */
    public ?string $frmpago;
    /**
     * @SerializedName("mtasacambio")
     */
    public ?string $mtasacambio;
    /**
     * @SerializedName("qregfcobro")
     */
    public ?string $qregfcobro;
    /**
     * @SerializedName("isucursalcliente")
     */
    public ?string $isucursalcliente;
}
