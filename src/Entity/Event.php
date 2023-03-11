<?php

namespace App\Entity; 

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $nom_event = null;
    
   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $discription_event = null;
    
   


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $image_event = null;
    

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut_event = null;
    
   

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_fin_event = null;

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $adresse_event = null;
    
    

    #[ORM\OneToMany(mappedBy: 'eventID', targetEntity: EventTicket::class)]
    private Collection $eventTick;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $status = null;
  
   

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reserve = null;

    public function __construct()
    {
        $this->eventTick = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->nom_event;
    }

    public function setNomEvent(string $nom_event): self
    {
        $this->nom_event = $nom_event;

        return $this;
    }

    public function getDiscriptionEvent(): ?string
    {
        return $this->discription_event;
    }

    public function setDiscriptionEvent(string $discription_event): self
    {
        $this->discription_event = $discription_event;

        return $this;
    }

    public function getImageEvent(): ?string
    {
        return $this->image_event;
    }

    public function setImageEvent(string $image_event): self
    {
        $this->image_event = $image_event;

        return $this;
    }

    public function getDateDebutEvent(): ?\DateTimeInterface
    {
        return $this->date_debut_event;
    }

    public function setDateDebutEvent(\DateTimeInterface $date_debut_event): self
    {
        $this->date_debut_event = $date_debut_event;

        return $this;
    }

    public function getDateFinEvent(): ?\DateTimeInterface
    {
        return $this->date_fin_event;
    }

    public function setDateFinEvent(\DateTimeInterface $date_fin_event): self
    {
        $this->date_fin_event = $date_fin_event;

        return $this;
    }

    public function getAdresseEvent(): ?string
    {
        return $this->adresse_event;
    }

    public function setAdresseEvent(string $adresse_event): self
    {
        $this->adresse_event = $adresse_event;

        return $this;
    }

    /**
     * @return Collection<int, EventTicket>
     */
    public function getEventTick(): Collection
    {
        return $this->eventTick;
    }

    public function addEventTick(EventTicket $eventTick): self
    {
        if (!$this->eventTick->contains($eventTick)) {
            $this->eventTick->add($eventTick);
            $eventTick->setEventID($this);
        }

        return $this;
    }

    public function removeEventTick(EventTicket $eventTick): self
    {
        if ($this->eventTick->removeElement($eventTick)) {
            // set the owning side to null (unless already changed)
            if ($eventTick->getEventID() === $this) {
                $eventTick->setEventID(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReserve(): ?string
    {
        return $this->reserve;
    }

    public function setReserve(?string $reserve): self
    {
        $this->reserve = $reserve;

        return $this;
    }

    public function __toString() {
        return $this->id;
    }

    
}
