<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
class DossierMedical
{


   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    public function __toString(){ 
    // Return a string representation of the object
        return   $this->getId() ;
}

    #[ORM\OneToOne(inversedBy: 'dossierMedical', cascade: ['persist', 'remove'])]
    private ?User $userId = null;

   

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: self::class)]
    private Collection $Ficheconsultations;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: Ficheconsultation::class)]
    private Collection $ficheconsultations;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "*****@gmail.com")]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('post:read')]
    private ?\DateTimeInterface  $date_naissance = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank (message: "le champ est vide")]
    #[Groups('post:read')]
    private ?string $vaccins = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champ est vide")]
    private ?string $maladies = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champ est vide")]
    private ?string $allergies = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champ est vide")]
    private ?string $analyses = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champ est vide")]
    private ?string $intervention_chirurgicale = null;

    public function __construct()
    {
        $this->Ficheconsultations = new ArrayCollection();
        $this->ficheconsultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDossierMedical(): ?self
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?self $dossierMedical): self
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFicheconsultations(): Collection
    {
        return $this->Ficheconsultations;
    }

    public function addFicheconsultation(self $ficheconsultation): self
    {
        if (!$this->Ficheconsultations->contains($ficheconsultation)) {
            $this->Ficheconsultations->add($ficheconsultation);
            $ficheconsultation->setDossierMedical($this);
        }

        return $this;
    }

    public function removeFicheconsultation(self $ficheconsultation): self
    {
        if ($this->Ficheconsultations->removeElement($ficheconsultation)) {
            // set the owning side to null (unless already changed)
            if ($ficheconsultation->getDossierMedical() === $this) {
                $ficheconsultation->setDossierMedical(null);
            }
        }

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getVaccins(): ?string
    {
        return $this->vaccins;
    }

    public function setVaccins(string $vaccins): self
    {
        $this->vaccins = $vaccins;

        return $this;
    }

    public function getMaladies(): ?string
    {
        return $this->maladies;
    }

    public function setMaladies(string $maladies): self
    {
        $this->maladies = $maladies;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getAnalyses(): ?string
    {
        return $this->analyses;
    }

    public function setAnalyses(string $analyses): self
    {
        $this->analyses = $analyses;

        return $this;
    }

    public function getInterventionChirurgicale(): ?string
    {
        return $this->intervention_chirurgicale;
    }

    public function setInterventionChirurgicale(string $intervention_chirurgicale): self
    {
        $this->intervention_chirurgicale = $intervention_chirurgicale;

        return $this;
    }
}
