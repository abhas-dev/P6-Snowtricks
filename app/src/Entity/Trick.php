<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity(fields: "name", message: "Ce trick existe deja.")]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le nom du trick est obligatoire')]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'La description du trick est obligatoire')]
    #[Assert\Length(min: 50, minMessage: 'La description du trick doit faire au moins 50 caractères')]
    private $description;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: TrickCategory::class, inversedBy: 'tricks')]
    #[Assert\NotBlank(message: 'Vous devez choisir une categorie')]
    private $trickCategory;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickImage::class, cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private $trickImages;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: TrickVideo::class, cascade: ['persist'], orphanRemoval: true)]
    #[Assert\Valid]
    private $trickVideos;

    #[ORM\OneToOne(targetEntity: TrickImage::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private $mainTrickImage;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tricks')]
    private $author;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Message::class, orphanRemoval: true)]
    private $messages;


    public function __construct()
    {
        $this->trickImages = new ArrayCollection();
        $this->trickVideos = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTrickCategory(): ?TrickCategory
    {
        return $this->trickCategory;
    }

    public function setTrickCategory(?TrickCategory $trickCategory): self
    {
        $this->trickCategory = $trickCategory;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTrickImages(): Collection
    {
        return $this->trickImages;
    }

    public function addTrickImage(TrickImage $trickImage): self
    {
        if (!$this->trickImages->contains($trickImage)) {
            $this->trickImages[] = $trickImage;
            $trickImage->setTrick($this);
        }

        return $this;
    }

    public function removeTrickImage(TrickImage $trickImage): self
    {
        if ($this->trickImages->removeElement($trickImage)) {
            // set the owning side to null (unless already changed)
            if ($trickImage->getTrick() === $this) {
                $trickImage->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrickVideo[]
     */
    public function getTrickVideos(): Collection
    {
        return $this->trickVideos;
    }

    public function addTrickVideo(TrickVideo $trickVideo): self
    {
        if (!$this->trickVideos->contains($trickVideo)) {
            $this->trickVideos[] = $trickVideo;
            $trickVideo->setTrick($this);
        }

        return $this;
    }

    public function removeTrickVideo(TrickVideo $trickVideo): self
    {
        if ($this->trickVideos->removeElement($trickVideo)) {
            // set the owning side to null (unless already changed)
            if ($trickVideo->getTrick() === $this) {
                $trickVideo->setTrick(null);
            }
        }

        return $this;
    }

    public function getMainTrickImage(): ?TrickImage
    {
        return $this->mainTrickImage;
    }

    public function setMainTrickImage(?TrickImage $mainTrickImage): self
    {
        $this->mainTrickImage = $mainTrickImage;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setTrick($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getTrick() === $this) {
                $message->setTrick(null);
            }
        }

        return $this;
    }
}
