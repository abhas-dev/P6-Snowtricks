<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Form\TrickType;
use App\Repository\TrickImageRepository;
use App\Repository\TrickRepository;
use App\Service\ImageService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;
    private ImageService $imageService;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, ImageService $imageService)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->imageService = $imageService;
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
    public function create(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $trickImages = $trick->getTrickImages();

            foreach($trickImages as $trickImage)
            {
                $this->imageService->moveImageToFinalDirectory($trickImage);
            }

            if($trickImages) $trick->setMainTrickImage($trickImages[0]);
            $trick->setSlug(strtolower($this->slugger->slug($trick->getName())));
            $trick->setCreatedAt(new \DateTime('now'));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le trick a bien été enregistré');

            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('trick/create.html.twig', compact('form'));
    }

    #[Route('/trick/{slug}/edit', name: 'trick_edit')]
    public function edit(Trick $trick, Request $request,TrickImageRepository $imageRepository)
    {
        $form = $this->createForm(TrickType::class, $trick);

//        /** @var Collection<TrickImage> $trickImages */
//        $trickImages = $trick->getTrickImages()->toArray();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
//            dd($form);
            foreach($trickImages = $trick->getTrickImages() as $trickImage)
            {
                if(!$trickImage->getId())
                {
                    $this->imageService->moveImageToFinalDirectory($trickImage);
                }
//                dd($trickImages);
//                $trickImage->setTrick($trick);
            }

//            $this->entityManager->persist($trickImages);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le trick a bien été modifié');

            return $this->redirectToRoute('trick_show', ["category_slug" => $trick->getTrickCategory()->getSlug(), "slug" => $trick->getSlug()]);
        }

        return $this->renderForm('trick/edit.html.twig', compact('form', 'trick'));
    }

    #[Route('/trick/{slug}/delete', name: 'trick_delete', methods: ['DELETE'])]
    public function deleteTrick()
    {

    }

    #[Route('/trick/{slug}/edit-tab', name: 'trick_edit-tab')]
    public function changeDivContent(string $slug, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        dd($data);
        return $this->json([

        ]);
    }
}