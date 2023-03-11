<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
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
    private ?string $email = null;

    

    #[ORM\Column(length: 255)]
    private ?string $date_naissance = null;

    #[ORM\Column(length: 255)]
    private ?string $vaccins = null;

    #[ORM\Column(length: 255)]
    private ?string $Maladies = null;

    #[ORM\Column(length: 255)]
    private ?string $Allérgies = null;

    #[ORM\Column(length: 255)]
    private ?string $Analyses = null;

    #[ORM\Column(length: 255)]
    private ?string $interventionchirurgicale = null;

   

    #[ORM\Column(length: 255)]
    private ?string $dossierID = null;

    #[ORM\OneToMany(mappedBy: 'dossierID', targetEntity: FicheConsultation::class)]
    private Collection $ficheConsultations;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $userID = null;

    #[ORM\OneToMany(mappedBy: 'dossier', targetEntity: FicheConsultation::class)]
    private Collection $FicheConsultations;

    public function __construct()
    {
        $this->ficheConsultations = new ArrayCollection();
        $this->FicheConsultations = new ArrayCollection();
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(string $date_naissance): self
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
        return $this->Maladies;
    }

    public function setMaladies(string $Maladies): self
    {
        $this->Maladies = $Maladies;

        return $this;
    }

    public function getAllérgies(): ?string
    {
        return $this->Allérgies;
    }

    public function setAllérgies(string $Allérgies): self
    {
        $this->Allérgies = $Allérgies;

        return $this;
    }

    public function getAnalyses(): ?string
    {
        return $this->Analyses;
    }

    public function setAnalyses(string $Analyses): self
    {
        $this->Analyses = $Analyses;

        return $this;
    }

    public function getInterventionchirurgicale(): ?string
    {
        return $this->interventionchirurgicale;
    }

    public function setInterventionchirurgicale(string $interventionchirurgicale): self
    {
        $this->interventionchirurgicale = $interventionchirurgicale;

        return $this;
    }

    

    public function getDossierID(): ?string
    {
        return $this->dossierID;
    }

    public function setDossierID(string $dossierID): self
    {
        $this->dossierID = $dossierID;

        return $this;
    }

    /**
     * @return Collection<int, FicheConsultation>
     */
    public function getFicheConsultations(): Collection
    {
        return $this->ficheConsultations;
    }

    public function addFicheConsultation(FicheConsultation $ficheConsultation): self
    {
        if (!$this->ficheConsultations->contains($ficheConsultation)) {
            $this->ficheConsultations->add($ficheConsultation);
            $ficheConsultation->setDossierID($this);
        }

        return $this;
    }

    public function removeFicheConsultation(FicheConsultation $ficheConsultation): self
    {
        if ($this->ficheConsultations->removeElement($ficheConsultation)) {
            // set the owning side to null (unless already changed)
            if ($ficheConsultation->getDossierID() === $this) {
                $ficheConsultation->setDossierID(null);
            }
        }

        return $this;
    }

    public function getUserID(): ?User
    {
        return $this->userID;
    }

    public function setUserID(?User $userID): self
    {
        $this->userID = $userID;

        return $this;
    }
}
