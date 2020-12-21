<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\Common\Collections\Collection;
use App\Repository\CommandeQuantityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CommandeQuantityRepository::class)
 */
class CommandeQuantity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Commandes::class, mappedBy="cmdId")
     */
    private $commande;
    

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer")
     */
    private $numCmd;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     */
    private $produit;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Commandes[]
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setCmdId($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getCmdId() === $this) {
                $commande->setCmdId(null);
            }
        }

        return $this;
    }

    public function getProduit(): ?Products
    {
        return $this->produit;
    }

    public function setProduit(?Products $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getNumCmd(): ?int
    {
        return $this->numCmd;
    }

    public function setNumCmd(int $numCmd): self
    {
        $this->numCmd = $numCmd;

        return $this;
    }
}
