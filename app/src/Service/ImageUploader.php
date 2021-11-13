<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private LoggerInterface $logger;
    private ParameterBagInterface $params;

    public function __construct(LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->logger = $logger;
        $this->params = $params;
    }

    public function upload(UploadedFile $file, string $newFilename)
    {
        try {
            $file->move(
                $this->params->get('trickImages_directory'),
                $newFilename
            );
        } catch (FileException $e){

            $this->logger->error('failed to upload image: ' . $e->getMessage());
            throw new FileException('Failed to upload file');
        }
    }

    public function removeUploadedImage(): void
    {
        $filesystem = new Filesystem();
        if($filesystem->exists('tempFilename'))
        {
            unlink($this->tempFilename);
        }
        $this->logger->error('failed to remove image: ' . $e->getMessage());
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getUrl()
    {
        return $this->id.'.'.$this->extension;
    }
}
