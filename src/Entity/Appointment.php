<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reason = null;


    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $hour = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bookingState = null;

    #[ORM\ManyToOne(inversedBy: 'doctorAppointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $doctor = null;

    #[ORM\ManyToOne(inversedBy: 'userAppointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;


    #[ORM\OneToOne(inversedBy: 'appointment', cascade: ['all'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeSlot $timeSlot = null;

    public function __toString()
    {
        return $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getBookingState(): ?string
    {
        return $this->bookingState;
    }

    public function setBookingState(?string $bookingState): self
    {
        $this->bookingState = $bookingState;

        return $this;
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

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getTimeSlot(): ?TimeSlot
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(?TimeSlot $timeSlot): self
    {
        // unset the owning side of the relation if necessary
        if ($timeSlot === null && $this->timeSlot !== null) {
            $this->timeSlot->setAppointment(null);
        }

        // set the owning side of the relation if necessary
        if ($timeSlot !== null && $timeSlot->getAppointment() !== $this) {
            $timeSlot->setAppointment($this);
        }

        $this->timeSlot = $timeSlot;

        return $this;
    }
}
