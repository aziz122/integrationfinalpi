<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $nom_prod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description_prod;

    /**
     * @ORM\Column(type="integer")
     */
    private $qte_prod;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_prod;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="please upload image")
     * @Assert\File(mimeTypes={"image/jpeg"})
     */
    private $img_prod;

    /**
     * @ORM\ManyToMany(targetEntity=Commande::class, mappedBy="produit")
     */
    private $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProd(): ?string
    {
        return $this->nom_prod;
    }

    public function setNomProd(string $nom_prod): self
    {
        $this->nom_prod = $nom_prod;

        return $this;
    }

    public function getDescriptionProd(): ?string
    {
        return $this->description_prod;
    }

    public function setDescriptionProd(?string $description_prod): self
    {
        $this->description_prod = $description_prod;

        return $this;
    }

    public function getQteProd(): ?int
    {
        return $this->qte_prod;
    }

    public function setQteProd(int $qte_prod): self
    {
        $this->qte_prod = $qte_prod;

        return $this;
    }

    public function getPrixProd(): ?float
    {
        return $this->prix_prod;
    }

    public function setPrixProd(float $prix_prod): self
    {
        $this->prix_prod = $prix_prod;

        return $this;
    }

    public function getImgProd()
    {
        return $this->img_prod;
    }

    public function setImgProd(?string $img_prod)
    {
        $this->img_prod = $img_prod;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeProduit($this);
        }

        return $this;
    }


}
