<?php

namespace App\Entity;
 
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{



    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('articles')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('articles')]
    private ?int $views = 0;

    #[ORM\Column(length: 255)]
    #[Groups('articles')]
    #[Assert\NotBlank (message: "le champ est vide !!")]
    /**
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "le sujet doit étre plus que  {{ limit }} caractères",
     *      maxMessage = "le sujet doit étre moins que {{ limit }} caractères"
     * )
     */ 
    private ?string $sujet = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank (message: "le champ est vide !!")]
    /**
   * @Assert\Length(
   *      min = 5,
   *      max = 9999,
   *      minMessage = "la contenu doit étre plus que {{ limit }} caractères",
   *      maxMessage = "la contenu doit étre moins que {{ limit }} caractères"
   * )
   */
  #[Groups("articles")]

    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    #[Groups("articles")]
    #[Assert\NotBlank (message: "Il faut inserer une image pour l'article !!")]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups("articles")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("articles")]
    private ?User $userId = null;
    #[Groups("articles")]
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): self
    {
        $this->views++;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }
    /** return the article subject to use it in the articals and comments section**/
    public function __toString() {
        return $this->sujet;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, commentaire>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(commentaire $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(commentaire $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }
}
