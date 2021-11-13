<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickImage;
use App\Form\TrickType;
use App\Repository\TrickRepository;
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
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $imageTricks = $trick->getTrickImages();
            if($imageTricks)
            {
                foreach($imageTricks as $image)
                {
                    $file = $image->getFile();
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                            $file->move(
                            $this->getParameter('trickImages_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        throw new FileException('Il y a eu un probleme lors de l\'envoi d\'un fichier');
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
//                    $trickImage = new TrickImage();
                    $image->setName($newFilename);
                    $trick->addTrickImage($image);

                }

                $trick->setSlug(strtolower($slugger->slug($trick->getName())));
                $trick->setCreatedAt(new \DateTime('now'));
                $this->entityManager->persist($trick);
                $this->entityManager->flush();

                return $this->redirectToRoute('homepage');
            }

        }

        return $this->renderForm('trick/create.html.twig', compact('form'));
    }
}
