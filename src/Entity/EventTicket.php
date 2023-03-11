<?php

namespace App\Entity;
 
use App\Repository\EventTicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventTicketRepository::class)]
class EventTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule_event = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ticket = null;

    #[ORM\Column(length: 255)]
    private ?string $valide_ticket = null;

    #[ORM\Column(length: 255)]
    private ?string $prix_ticket = null;

    #[ORM\ManyToOne(inversedBy: 'eventTickets')]
    private ?user $userID = null;

    #[ORM\ManyToOne(inversedBy: 'eventTick')]
    private ?Event $eventID = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description_ticket = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reserve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatriculeEvent(): ?string
    {
        return $this->matricule_event;
    }

    public function setMatriculeEvent(string $matricule_event): self
    {
        $this->matricule_event = $matricule_event;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateTicket(): ?\DateTimeInterface
    {
        return $this->date_ticket;
    }

    public function setDateTicket(\DateTimeInterface $date_ticket): self
    {
        $this->date_ticket = $date_ticket;

        return $this;
    }

    public function getValideTicket(): ?string
    {
        return $this->valide_ticket;
    }

    public function setValideTicket(string $valide_ticket): self
    {
        $this->valide_ticket = $valide_ticket;

        return $this;
    }

    public function getPrixTicket(): ?string
    {
        return $this->prix_ticket;
    }

    public function setPrixTicket(string $prix_ticket): self
    {
        $this->prix_ticket = $prix_ticket;

        return $this;
    }

    public function getUserID(): ?user
    {
        return $this->userID;
    }

    public function setUserID(?user $userID): self
    {
        $this->userID = $userID;

        return $this;
    }

    public function getEventID(): ?Event
    {
        return $this->eventID;
    }

    public function setEventID(?Event $eventID): self
    {
        $this->eventID = $eventID;

        return $this;
    }

    public function getDescriptionTicket(): ?string
    {
        return $this->description_ticket;
    }

    public function setDescriptionTicket(?string $description_ticket): self
    {
        $this->description_ticket = $description_ticket;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

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
}
