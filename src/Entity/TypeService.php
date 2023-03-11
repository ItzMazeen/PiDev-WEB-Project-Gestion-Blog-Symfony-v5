<?php

namespace App\Entity;

use App\Repository\TypeServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TypeServiceRepository::class)]
class TypeService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $NomType = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champs est vide!")]
    private ?string $Description = null;

    #[ORM\OneToMany(mappedBy: 'TypeService', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->NomType;
    }

    public function setNomType(string $NomType): self
    {
        $this->NomType = $NomType;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setTypeService($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getTypeService() === $this) {
                $service->setTypeService(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this -> getNomType();
    }
}
