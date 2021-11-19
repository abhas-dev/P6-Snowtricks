<?php

namespace App\Controller;

use App\Repository\TrickImageRepository;
use App\Repository\TrickRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickImageController extends AbstractController
{
    private TrickRepository $trickRepository;
    private ImageService $imageService;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ImageService $imageService, TrickRepository $trickRepository)
    {
        $this->imageService = $imageService;
        $this->entityManager = $entityManager;
        $this->trickRepository = $trickRepository;
    }

    #[Route('/trick/{slug}/image-delete/{id}', name: 'trick_image-delete', methods: ['DELETE'])]
    public function deleteImage(string $slug, int $id, TrickImageRepository $trickImageRepository, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $trickImage = $trickImageRepository->findOneBy(['id' => $id]);

        if(!$trickImage)
        {
            return $this->json([
                'code' => '403',
                'message' => "L'image n'existe pas"
            ], 403);
        }
        if($this->isCsrfTokenValid('delete-image', $data['_token']))
        {
            $this->imageService->removeUploadedImage($trickImage);
            $this->entityManager->remove($trickImage);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Image supprimée'
            ], 200);
        }

        return $this->json([
            'code' => 400,
            'message' => 'Token Invalide'
        ], 400);
    }

    #[Route('/trick/{slug}/mainPicture/{id}', name: 'trick_setTricksMainPicture')]
    public function setTricksMainPicture(string $slug, int $id, TrickImageRepository $imageRepository): Response
    {
        $trick = $this->trickRepository->findOneBy(compact('slug'));
        try{
            $trick->setMainTrickImage($imageRepository->find($id));
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'success'
            ], 200);
        }catch(\Exception $exception){
            throw $this->createNotFoundException("La demande n'a pas pu etre éxécutée" . $exception->getMessage());
//            return $this->json([
//                'code' => 400,
//                'message' => 'error'
//            ], 400);
        }
    }
}
