<?php

namespace App\Entity\Order;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Settlement
{
    /**
     * @SerializedName("parcial")
     */
    public ?string $parcial;
    /**
     * @SerializedName("descuento")
     */
    public ?string $descuento;
    /**
     * @SerializedName("iva")
     */
    public ?string $iva;
    /**
     * @SerializedName("total")
     */
    public ?string $total;
}
