<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $NomService = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $Proprietaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $id_type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '0')]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $Prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?\DateTimeInterface $Date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?\DateTimeInterface $Date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?TypeService $TypeService = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->NomService;
    }

    public function setNomService(string $NomService): self
    {
        $this->NomService = $NomService;

        return $this;
    }

    public function getProprietaire(): ?string
    {
        return $this->Proprietaire;
    }

    public function setProprietaire(string $Proprietaire): self
    {
        $this->Proprietaire = $Proprietaire;

        return $this;
    }

    public function getIdType(): ?string
    {
        return $this->id_type;
    }

    public function setIdType(string $id_type): self
    {
        $this->id_type = $id_type;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->Prix;
    }

    public function setPrix(string $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->Date_debut;
    }

    public function setDateDebut(\DateTimeInterface $Date_debut): self
    {
        $this->Date_debut = $Date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->Date_fin;
    }

    public function setDateFin(\DateTimeInterface $Date_fin): self
    {
        $this->Date_fin = $Date_fin;

        return $this;
    }

    public function getTypeService(): ?TypeService
    {
        return $this->TypeService;
    }

    public function setTypeService(?TypeService $TypeService): self
    {
        $this->TypeService = $TypeService;

        return $this;
    }

    public function __toString()
    {
        return $this -> getTypeService();
    }
}
