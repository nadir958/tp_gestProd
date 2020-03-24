<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="order_product")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderDetail", mappedBy="product_order")
     */
    private $order_detail;

    public function __construct()
    {
        $this->order_detail = new ArrayCollection();
        $this->setCreateAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    /**
     * @return Collection|OrderDetail[]
     */
    public function getOrderDetail(): Collection
    {
        return $this->order_detail;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->order_detail->contains($orderDetail)) {
            $this->order_detail[] = $orderDetail;
            $orderDetail->setProductOrder($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->order_detail->contains($orderDetail)) {
            $this->order_detail->removeElement($orderDetail);
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProductOrder() === $this) {
                $orderDetail->setProductOrder(null);
            }
        }

        return $this;
    }
}
