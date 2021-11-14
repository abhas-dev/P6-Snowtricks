<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    private TrickRepository $trickRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TrickRepository $trickRepository, EntityManagerInterface $entityManager)
    {
        $this->trickRepository = $trickRepository;
        $this->entityManager = $entityManager;
    }

//    #[Route('/{category_slug}/{slug}', name: 'trick_show')]
//    public function show(string $slug): Response
//    {
//        $trick = $this->trickRepository->findOneBy(compact('slug'));
//
//        if(!$trick)
//        {
//            throw $this->createNotFoundException("Ce trick n'existe pas");
//        }
//
//        return $this->render('trick/show.html.twig', compact('trick'));
//    }

    #[Route('/{category_slug}/{slug}', name: 'trick_show', priority: -1)]
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', compact('trick'));
    }

    #[Route('/trick/create', name: 'trick_create')]
    public function create(Request $request, SluggerInterface $slugger, ImageService $imageService): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $trickImages = $trick->getTrickImages();

                foreach($trickImages as $trickImage)
                {
                    $imageService->moveImageToFinalDirectory($trickImage);
                  }
            
            $trick->setSlug(strtolower($slugger->slug($trick->getName())));
            $trick->setCreatedAt(new \DateTime('now'));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('trick/create.html.twig', compact('form'));
    }
}
