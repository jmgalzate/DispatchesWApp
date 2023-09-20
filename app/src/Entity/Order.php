<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderHeader $encabezado = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderInvoiceSettlement $liquidacion = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?OrderMainData $datosprincipales = null;

    #[ORM\OneToMany(mappedBy: 'orderid', targetEntity: OrderProduct::class, orphanRemoval: true)]
    private Collection $listaproductos;

    #[ORM\Column(length: 255)]
    private ?string $qoprsok = null;

    public function __construct(
        OrderHeader $encabezado,
        OrderInvoiceSettlement $liquidacion,
        OrderMainData $datosprincipales,
        Collection $listaproductos = null,
        string $qoprsok
    ) {
        $this->encabezado = $encabezado;
        $this->liquidacion = $liquidacion;
        $this->datosprincipales = $datosprincipales;
        $this->qoprsok = $qoprsok;
        $this->listaproductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEncabezado(): ?OrderHeader
    {
        return $this->encabezado;
    }

    public function setEncabezado(OrderHeader $encabezado): static
    {
        $this->encabezado = $encabezado;

        return $this;
    }

    public function setEncabezadoNewUser(string $username): static
    {
        $this->encabezado->setIusuarioult($username);

        return $this;
    }

    public function getLiquidacion(): ?OrderInvoiceSettlement
    {
        return $this->liquidacion;
    }

    public function setLiquidacion(OrderInvoiceSettlement $liquidacion): static
    {
        $this->liquidacion = $liquidacion;

        return $this;
    }

    public function getDatosprincipales(): ?OrderMainData
    {
        return $this->datosprincipales;
    }

    public function setDatosprincipales(OrderMainData $datosprincipales): static
    {
        $this->datosprincipales = $datosprincipales;

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getListaproductos(): Collection
    {
        return $this->listaproductos;
    }

    public function addListaproducto(OrderProduct $listaproducto): static
    {
        if (!$this->listaproductos->contains($listaproducto)) {
            $this->listaproductos->add($listaproducto);
            $listaproducto->setOrderid($this);
        }

        return $this;
    }

    public function removeListaproducto(OrderProduct $listaproducto): static
    {
        if ($this->listaproductos->removeElement($listaproducto)) {
            // set the owning side to null (unless already changed)
            if ($listaproducto->getOrderid() === $this) {
                $listaproducto->setOrderid(null);
            }
        }

        return $this;
    }

    public function getQoprsok(): ?string
    {
        return $this->qoprsok;
    }

    public function setQoprsok(string $qoprsok): static
    {
        $this->qoprsok = $qoprsok;

        return $this;
    }
}
