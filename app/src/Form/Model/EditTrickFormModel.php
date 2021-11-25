<?php

namespace App\Form\Model;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use App\Entity\TrickImage;
use App\Entity\TrickVideo;

class EditTrickFormModel
{
    public string $name;
    public string $description;
    public $trickCategory;
    public $trickVideo;
    public $trickImage;
    public $newTrickImages;

    public static function fromTrick(Trick $trick): self
    {
        $trickData = new self();
        $trickData->name = $trick->getName();
        $trickData->description = $trick->getDescription();
        $trickData->trickCategory = $trick->getTrickCategory();
        $trickData->trickVideo = $trick->getTrickVideos();
        $trickData->trickImage = $trick->getTrickImages();

        return $trickData;
    }
}