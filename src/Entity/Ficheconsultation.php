<?php

namespace App\Entity;

use App\Repository\FicheconsultationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DateMutableType;
use Symfony\Component\Serializer\Annotation\Groups;





#[ORM\Entity(repositoryClass: FicheconsultationRepository::class)]
class Ficheconsultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ficheconsultations')]
    private ?User $doctor = null;

    #[ORM\ManyToOne(inversedBy: 'ficheconsultations')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?DossierMedical $dossierMedical = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('post:read')]
    #[Assert\EqualTo(value:'today',message: "la date doit etre la meme que la date d'aujourd'hui")]
    private ?\DateTimeInterface $date_consultation = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]

    private ?string $lastName = null;

    #[ORM\Column(length: 255) ]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $specialite = null;

   

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $traitement= null;

    #[ORM\Column(length: 255)]
    #[Groups('post:read')]
    #[Assert\NotBlank (message: "le champo est vide")]
    private ?string $reccomendation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDoctor(): ?User
    {
        return $this->doctor;
    }

    public function setDoctor(?User $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): self
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

    public function getDateConsultation(): ?\DateTimeInterface
    {
        return $this->date_consultation;
    }

    public function setDateConsultation(\DateTimeInterface $date_consultation): self
    {
        $this->date_consultation = $date_consultation;

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

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): self
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getReccomendation(): ?string
    {
        return $this->reccomendation;
    }

    public function setReccomendation(string $reccomendation): self
    {
        $this->reccomendation = $reccomendation;

        return $this;
    }
}
