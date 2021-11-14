<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\TrickImage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    private LoggerInterface $logger;
    private ParameterBagInterface $params;
    private SluggerInterface $slugger;
    private Filesystem $filesystem;
    private string $imageNewName;
    private $targetDirectory;

    public function __construct($targetDirectory, LoggerInterface $logger, ParameterBagInterface $params, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->logger = $logger;
        $this->params = $params;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
        $this->targetDirectory = $targetDirectory;
    }

//    public function moveImageToFinalDirectory(UploadedFile $file)
//    {
//        $newFilename = $this->generateNewFileName($file);
//        try {
//            $file->move(
//                $this->getTargetDirectory(),
//                $newFilename
//            );
//        } catch (FileException $e) {
//            // ... handle exception if something happens during file upload
//            $this->logger->error('failed to upload image: ' . $e->getMessage());
//            throw new FileException('Il y a eu un probleme lors de l\'envoi d\'un fichier');
//        }
//    }

    public function moveImageToFinalDirectory(TrickImage $trickImage)
    {
        $file = $trickImage->getFile();
        $newFilename = $this->generateNewFileName($file);
        $trickImage->setFilename($newFilename);
        try {
            $file->move(
                $this->getTargetDirectory(),
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            $this->logger->error('failed to upload image: ' . $e->getMessage());
            throw new FileException('Il y a eu un probleme lors de l\'envoi d\'un fichier');
        }
    }

    private function generateNewFileName(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $this->imageNewName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        return $this->imageNewName;
    }

    public function getImageNewName(): string
    {
        return $this->imageNewName;
    }

    public function removeUploadedImage(): void
    {
        if($this->filesystem->exists('tempFilename'))
        {
            unlink($this->tempFilename);
        }
        $this->logger->error('failed to remove image: ' . $e->getMessage());
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
//
//    public function getPublicPath(string $path): string
//    {
//        return 'uploads/'.$path;
//    }

//
//    protected function getUploadRootDir()
//    {
//        // On retourne le chemin relatif vers l'image
//        return __DIR__.'/../../../../web/'.$this->getUploadDir();
//    }
//
//    public function getUrl()
//    {
//        return $this->id.'.'.$this->extension;
//    }
}
