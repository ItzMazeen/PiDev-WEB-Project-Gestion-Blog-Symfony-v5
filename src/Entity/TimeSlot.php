<?php

namespace App\Entity;

use App\Repository\TimeSlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Regex("/^\d{2}:\d{2}$/")]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Regex("/^\d{2}:\d{2}$/")]
    #[Assert\GreaterThan(
        propertyPath: "startTime",
        message: "dayEnds time must be after dayStars time . "
    )]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 0, max: 255)]
    #[Assert\Choice(choices: ["available", "not-available"])]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(min: 0, max: 255)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9 .,;:'\"()_-]*$/",
        message: "Invalid characters in input"
    )]
    private ?string $reason = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 0, max: 600)]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z0-9 .,;:'\"()_-]*$/",
        message: "Invalid characters in input"
    )]
    private ?string $note = null;

    #[ORM\Column(nullable: true)]
    private ?int $indexSlot = null;

    #[ORM\OneToOne(mappedBy: 'timeSlot', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    #[ORM\ManyToOne(inversedBy: 'timeSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalandarDay $calandarDay = null;




    //  relations *
    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): self
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getCalandarDay(): ?CalandarDay
    {
        return $this->calandarDay;
    }

    public function setCalandarDay(?CalandarDay $calandarDay): self
    {
        $this->calandarDay = $calandarDay;

        return $this;
    }


    public function getIndexSlot(): ?int
    {
        return $this->indexSlot;
    }

    public function setIndexSlot(?int $indexSlot): self
    {
        $this->indexSlot = $indexSlot;

        return $this;
    }
}
