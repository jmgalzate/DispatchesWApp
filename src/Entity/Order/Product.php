<?php

namespace App\Entity\Order;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Product
{
    /**
     * @SerializedName("irecurso")
     */
    public ?string  $irecurso;
    /**
     * @SerializedName("itiporec")
     */
    public ?string  $itiporec;
    /**
     * @SerializedName("icc")
     */
    public ?string  $icc;
    /**
     * @SerializedName("sobserv")
     */
    public ?string  $sobserv;
    /**
     * @SerializedName("dato1")
     */
    public ?string  $dato1;
    /**
     * @SerializedName("dato2")
     */
    public ?string  $dato2;
    /**
     * @SerializedName("dato3")
     */
    public ?string  $dato3;
    /**
     * @SerializedName("dato4")
     */
    public ?string  $dato4;
    /**
     * @SerializedName("dato5")
     */
    public ?string  $dato5;
    /**
     * @SerializedName("dato6")
     */
    public ?string  $dato6;
    /**
     * @SerializedName("iinventario")
     */
    public ?string  $iinventario;
    /**
     @SerializedName("qrecurso")
     */
    public ?int     $qrecurso;
    /**
     * @SerializedName("mprecio")
     */
    public ?float   $mprecio;
    /**
     * @SerializedName("qporcdescuento")
     */
    public ?float   $qporcdescuento;
    /**
     * @SerializedName("qporciva")
     */
    public ?string  $qporciva;
    /**
     * @SerializedName("mvrtotal")
     */
    public ?float   $mvrtotal;
    /**
     * @SerializedName("valor1")
     */
    public ?string  $valor1;
    /**
     * @SerializedName("valor2")
     */
    public ?string  $valor2;
    /**
     * @SerializedName("valor3")
     */
    public ?string  $valor3;
    /**
     * @SerializedName("valor4")
     */
    public ?string  $valor4;
    /**
     * @SerializedName("qrecurso2")
     */
    public ?string  $qrecurso2;
}
