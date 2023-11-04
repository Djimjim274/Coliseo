<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;
    
     /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Familia::class, inversedBy="productos", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $familia;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $cod;

    /**
     * @ORM\OneToMany(targetEntity=PedidosProductos::class, mappedBy="producto", orphanRemoval=true)
     */
    private $pedidosProductos;

    /**
     * @ORM\Column(type="blob")
     */
    private $img;

    public function __construct()
    {
        $this->pedidosProductos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

     public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFamilia(): ?Familia
    {
        return $this->familia;
    }

    public function setFamilia(?Familia $familia): self
    {
        $this->familia = $familia;

        return $this;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }

    public function setCod(string $cod): self
    {
        $this->cod = $cod;

        return $this;
    }

    /**
     * @return Collection<int, PedidosProductos>
     */
    public function getPedidosProductos(): Collection
    {
        return $this->pedidosProductos;
    }

    public function addPedidosProducto(PedidosProductos $pedidosProducto): self
    {
        if (!$this->pedidosProductos->contains($pedidosProducto)) {
            $this->pedidosProductos[] = $pedidosProducto;
            $pedidosProducto->setProducto($this);
        }

        return $this;
    }

    public function removePedidosProducto(PedidosProductos $pedidosProducto): self
    {
        if ($this->pedidosProductos->removeElement($pedidosProducto)) {
            // set the owning side to null (unless already changed)
            if ($pedidosProducto->getProducto() === $this) {
                $pedidosProducto->setProducto(null);
            }
        }

        return $this;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function setImg($img): self
    {
        $this->img = $img;

        return $this;
    }
}
