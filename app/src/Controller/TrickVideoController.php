<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickVideoController extends AbstractController
{
    #[Route('/trick/{slug}/video-delete/{id}', name: 'trick_video-delete', methods: ['DELETE'])]
    public function deleteImage(int $id, string $slug, TrickVideoRepository $trickVideoRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $trickVideo = $trickVideoRepository->find($id);
        $data = json_decode($request->getContent(), true);

        if(!$trickVideo)
        {
            return $this->json([
                'code' => '403',
                'message' => "La video n'existe pas"
            ], 403);
        }

        if($this->isCsrfTokenValid('delete-image', $data['_token']))
        {
            $trickVideoId = $trickVideo->getId();
            $manager->remove($trickVideo);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Video supprimée',
                'trickVideoId' => $trickVideoId
            ]);
        }

        return $this->json([
            'code' => 400,
            'message' => 'Token Invalide'
        ], 400);
    }
}
