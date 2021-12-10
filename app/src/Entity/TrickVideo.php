<?php

namespace App\Entity;

use App\Repository\TrickVideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TrickVideoRepository::class)]
class TrickVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Regex(
        pattern: "/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^'&?\/\s]{11})/",
        message: "Ce lien n'est pas valide",
        match: true
    )]
    #[Assert\NotBlank(message: 'Veuillez saisir un lien valide')]
    private $url;

    #[ORM\ManyToOne(targetEntity: VideoProvider::class, inversedBy: 'trickVideos')]
    #[ORM\JoinColumn(nullable: true)]
    private $provider;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'trickVideos')]
    private $trick;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $this->convertLinkToEmbed($url);

        return $this;
    }

    public function getProvider(): ?VideoProvider
    {
        return $this->provider;
    }

    public function setProvider(?VideoProvider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    private function convertLinkToEmbed(string $url): string|null
    {
        $youtube = "/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/";
        $youtubeEmbed = "/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^'&?\/\s]{11})/gi";
        if (preg_match($youtube, $url, $matches)) {
            return "//www.youtube.com/embed/" . $matches[1];
        }

        return null;
    }
}

