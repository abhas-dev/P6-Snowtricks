<?php

namespace App\Entity;

use App\Repository\TrickImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickImageRepository::class)]
class TrickImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'image est obligatoire')]
    private $name;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'trickImages')]
    private $trick;

    /** @var UploadedFile */
    #[Assert\Image(
        maxSize: 1024000,
        minWidth: 200,
        maxWidth: 1920,
        minHeight: 200,
        maxHeight: 1920,
        mimeTypes: ['image/jpeg', 'image/jpg', 'image/png'],
        mimeTypesMessage: 'Veuillez entrer un fichier valide',
        allowPortrait: false,
        allowPortraitMessage: "L'image doit etre au format paysage"
    )]
    #[Assert\NotBlank(message: 'Veuillez saisir une image ou supprimer le champ')]
    private $file;

    #[ORM\Column(type: 'string', length: 255)]
    private $filename;

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

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
//        $params = new ParameterBag();
//        return $params->get('trickImages_directory').'/'.$this->getName();
        return 'uploads/tricks/'.$this->getFilename();
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }
}
