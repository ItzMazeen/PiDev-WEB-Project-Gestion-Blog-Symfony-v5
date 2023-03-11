<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CalandarDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalandarDayRepository::class)]
#[ORM\Table(name: "calandar_day")] // this is the name of the table in the database not entity name
#[ORM\UniqueConstraint(name: "doctor_date_idx", columns: ["doctor_id", "date"])]
class CalandarDay
{
    // Safe assert for all string input
    // #[Assert\Regex(
    //     pattern: '/^[a-zA-Z0-9!@#$%^&*()_+{}\[\]:;.,?-]*$/',
    //     message: 'The password can only contain letters, digits, and the following symbols: !@#$%^&*()_+{}[]:;.,?-'
    // )]


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\LessThanOrEqual('+3 months')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    // #[Assert\Regex("/^\d{2}:\d{2}$/")]
    private ?\DateTimeInterface $dayStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(
        propertyPath: "dayStart",
        message: "dayEnds time must be after dayStars time . "
    )]
    // #[Assert\Regex("/^\d{2}:\d{2}$/")]
    private ?\DateTimeInterface $dayEnd = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(['min' => 5, 'max' => 720])]
    private ?int $sessionDuration = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank]
    // #[Assert\Regex("/^\d{2}:\d{2}$/")]
    private ?\DateTimeInterface $lunchBreakStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(
        propertyPath: "lunchBreakStart",
        message: "time of endLunchTime must be after of time of startLunchTime"
    )]
    private ?\DateTimeInterface $lunchBreakEnd = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalTimeSlots = null;

    #[ORM\ManyToOne(inversedBy: 'calanderDays')]
    #[Assert\NotBlank]
    private ?User $doctor = null;

    #[ORM\OneToMany(mappedBy: 'calandarDay', targetEntity: TimeSlot::class, orphanRemoval: true)]
    private Collection $timeSlots;

    public function __construct()
    {
        $this->timeSlots = new ArrayCollection();
    }

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

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDayStart(): ?\DateTimeInterface
    {
        return $this->dayStart;
    }

    public function setDayStart(\DateTimeInterface $dayStart): self
    {
        $this->dayStart = $dayStart;

        return $this;
    }

    public function getDayEnd(): ?\DateTimeInterface
    {
        return $this->dayEnd;
    }

    public function setDayEnd(\DateTimeInterface $dayEnd): self
    {
        $this->dayEnd = $dayEnd;

        return $this;
    }

    public function getSessionDuration(): ?int
    {
        return $this->sessionDuration;
    }

    public function setSessionDuration(int $sessionDuration): self
    {
        $this->sessionDuration = $sessionDuration;

        return $this;
    }

    public function getLunchBreakStart(): ?\DateTimeInterface
    {
        return $this->lunchBreakStart;
    }

    public function setLunchBreakStart(?\DateTimeInterface $lunchBreakStart): self
    {
        $this->lunchBreakStart = $lunchBreakStart;

        return $this;
    }

    public function getLunchBreakEnd(): ?\DateTimeInterface
    {
        return $this->lunchBreakEnd;
    }

    public function setLunchBreakEnd(?\DateTimeInterface $lunchBreakEnd): self
    {
        $this->lunchBreakEnd = $lunchBreakEnd;

        return $this;
    }

    public function getTotalTimeSlots(): ?int
    {
        return $this->totalTimeSlots;
    }

    public function setTotalTimeSlots(?int $totalTimeSlots): self
    {
        $this->totalTimeSlots = $totalTimeSlots;

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

    /**
     * @return Collection<int, TimeSlot>
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    public function addTimeSlot(TimeSlot $timeSlot): self
    {
        if (!$this->timeSlots->contains($timeSlot)) {
            $this->timeSlots->add($timeSlot);
            $timeSlot->setCalandarDay($this);
        }

        return $this;
    }

    public function removeTimeSlot(TimeSlot $timeSlot): self
    {
        if ($this->timeSlots->removeElement($timeSlot)) {
            // set the owning side to null (unless already changed)
            if ($timeSlot->getCalandarDay() === $this) {
                $timeSlot->setCalandarDay(null);
            }
        }

        return $this;
    }
}
