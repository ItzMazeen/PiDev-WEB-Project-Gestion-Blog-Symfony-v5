<?php

namespace App\Entity;

use App\Repository\FicheConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheConsultationRepository::class)]
class FicheConsultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $Traitements = null;

    #[ORM\Column(length: 255)]
    private ?string $recommendation = null;

    #[ORM\Column(length: 255)]
    private ?string $spécialité_docteur = null;

    #[ORM\Column(length: 255)]
    private ?string $date_consultation = null;

    
    public function getId(): ?int
    {
        return $this->id;
        
    }

   

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTraitements(): ?string
    {
        return $this->Traitements;
    }

    public function setTraitements(string $Traitements): self
    {
        $this->Traitements = $Traitements;

        return $this;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(string $recommendation): self
    {
        $this->recommendation = $recommendation;

        return $this;
    }

    public function getSpécialitéDocteur(): ?string
    {
        return $this->spécialité_docteur;
    }

    public function setSpécialitéDocteur(string $spécialité_docteur): self
    {
        $this->spécialité_docteur = $spécialité_docteur;

        return $this;
    }

    public function getDateConsultation(): ?string
    {
        return $this->date_consultation;
    }

    public function setDateConsultation(string $date_consultation): self
    {
        $this->date_consultation = $date_consultation;

        return $this;
    }

    

}
