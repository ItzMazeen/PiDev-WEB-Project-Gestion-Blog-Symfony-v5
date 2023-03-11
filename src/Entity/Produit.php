<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $quantite_produit = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie_produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantiteProduit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantiteProduit(int $quantite_produit): self
    {
        $this->quantite_produit = $quantite_produit;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->image_produit;
    }

    public function setImageProduit(string $image_produit): self
    {
        $this->image_produit = $image_produit;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCategorieProduit(): ?Categorie
    {
        return $this->categorie_produit;
    }

    public function setCategorieProduit(?Categorie $categorie_produit): self
    {
        $this->categorie_produit = $categorie_produit;

        return $this;
    }
}
