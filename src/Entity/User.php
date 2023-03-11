<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Groups("articles")]

    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups("articles")]

    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $speciality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $licence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last_login = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extra1_rdv = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $age = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'userID', targetEntity: EventTicket::class)]
    private Collection $eventTickets;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: CalandarDay::class)]
    private Collection $calanderDays;

    #[ORM\OneToMany(mappedBy: 'doctor', targetEntity: Appointment::class)]
    private Collection $doctorAppointments;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class)]
    private Collection $userAppointments;

    #[ORM\OneToMany(mappedBy: 'userID', targetEntity: FicheConsultation::class)]
    private Collection $ficheConsultations;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->eventTickets = new ArrayCollection();
        $this->calanderDays = new ArrayCollection();
        $this->doctorAppointments = new ArrayCollection();
        $this->userAppointments = new ArrayCollection();
        $this->ficheConsultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /** return the user name and lastname to use them in the articals and comments section**/
    public function __toString() {
        return $this->firstName . " " . $this->lastName;
        ;
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

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(?string $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLastLogin(): ?string
    {
        return $this->last_login;
    }

    public function setLastLogin(?string $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getExtra1Rdv(): ?string
    {
        return $this->extra1_rdv;
    }

    public function setExtra1Rdv(?string $extra1_rdv): self
    {
        $this->extra1_rdv = $extra1_rdv;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUserId($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUserId() === $this) {
                $article->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUserId($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUserId() === $this) {
                $commentaire->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventTicket>
     */
    public function getEventTickets(): Collection
    {
        return $this->eventTickets;
    }

    public function addEventTicket(EventTicket $eventTicket): self
    {
        if (!$this->eventTickets->contains($eventTicket)) {
            $this->eventTickets->add($eventTicket);
            $eventTicket->setUserID($this);
        }

        return $this;
    }

    public function removeEventTicket(EventTicket $eventTicket): self
    {
        if ($this->eventTickets->removeElement($eventTicket)) {
            // set the owning side to null (unless already changed)
            if ($eventTicket->getUserID() === $this) {
                $eventTicket->setUserID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CalandarDay>
     */
    public function getCalanderDays(): Collection
    {
        return $this->calanderDays;
    }

    public function addCalanderDay(CalandarDay $calanderDay): self
    {
        if (!$this->calanderDays->contains($calanderDay)) {
            $this->calanderDays->add($calanderDay);
            $calanderDay->setDoctor($this);
        }

        return $this;
    }

    public function removeCalanderDay(CalandarDay $calanderDay): self
    {
        if ($this->calanderDays->removeElement($calanderDay)) {
            // set the owning side to null (unless already changed)
            if ($calanderDay->getDoctor() === $this) {
                $calanderDay->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getDoctorAppointments(): Collection
    {
        return $this->doctorAppointments;
    }

    public function addDoctorAppointment(Appointment $doctorAppointment): self
    {
        if (!$this->doctorAppointments->contains($doctorAppointment)) {
            $this->doctorAppointments->add($doctorAppointment);
            $doctorAppointment->setDoctor($this);
        }

        return $this;
    }

    public function removeDoctorAppointment(Appointment $doctorAppointment): self
    {
        if ($this->doctorAppointments->removeElement($doctorAppointment)) {
            // set the owning side to null (unless already changed)
            if ($doctorAppointment->getDoctor() === $this) {
                $doctorAppointment->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getUserAppointments(): Collection
    {
        return $this->userAppointments;
    }

    public function addUserAppointment(Appointment $userAppointment): self
    {
        if (!$this->userAppointments->contains($userAppointment)) {
            $this->userAppointments->add($userAppointment);
            $userAppointment->setPatient($this);
        }

        return $this;
    }

    public function removeUserAppointment(Appointment $userAppointment): self
    {
        if ($this->userAppointments->removeElement($userAppointment)) {
            // set the owning side to null (unless already changed)
            if ($userAppointment->getPatient() === $this) {
                $userAppointment->setPatient(null);
            }
        }

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
            $ficheConsultation->setUserID($this);
        }

        return $this;
    }
 public function removeFicheConsultation(FicheConsultation $ficheConsultation): self
    {
        if ($this->ficheConsultations->removeElement($ficheConsultation)) {
            // set the owning side to null (unless already changed)
            if ($ficheConsultation->getUserID() === $this) {
                $ficheConsultation->setUserID(null);
            }
        }

        return $this;
    }

}
