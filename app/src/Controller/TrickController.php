<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\MessageType;
use App\Form\Model\EditTrickFormModel;
use App\Form\Trick\EditTrickType;
use App\Form\Trick\TrickType;
use App\Service\ImageService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;
    private ImageService $imageService;
    private Paginator $paginator;

    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger, ImageService $imageService, Paginator $paginator)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        $this->imageService = $imageService;
        $this->paginator = $paginator;
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

    #[Route('/{category_slug}/{slug}', name: 'trick_show', methods: ['GET', 'POST'], priority: -1)]
    public function show(Trick $trick, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        $messagesPagination = $this->paginator->paginate(10, $trick->getId());
        [
            $messages,
            $totalPages,
            $currentPage
        ] = $messagesPagination;

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$this->getUser() || !($this->getUser() instanceof User))
            {
                return $this->redirectToRoute('homepage');
            }

            /** @var User $user */
            $user = $this->getUser();

            $message->setCreatedAt(new \DateTime('now'));
            $message->setTrick($trick);
            $message->setAuthor($user);
            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('trick_show', ['category_slug' => $trick->getTrickCategory()->getSlug(), 'slug' => $trick->getSlug()]);
        }

        return $this->renderForm('trick/show.html.twig', compact('trick', 'form', 'messages', 'totalPages', 'currentPage'));
    }

    #[Route('/trick/create', name: 'trick_create')]
    #[IsGranted('ROLE_USER', message: 'Vous devez etre connecté pour pouvoir creer un trick')]
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
                $this->imageService->moveTrickImageToFinalDirectory($trickImage);
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
    public function edit(Trick $trick, Request $request): Response
    {
//        if(!$trick){
//            throw $this->createNotFoundException("Ce trick n'existe pas");
//        }

        $this->denyAccessUnlessGranted('CAN_EDIT', $trick, "Vous n'avez pas le droit d'acceder à ce trick");

        // On injecte les données du trick dans le model (DTO)
        $trickModel = EditTrickFormModel::fromTrick($trick);
        // On crée un formulaire de type Edit et on lui associe la classe du DTO
        $form = $this->createForm(EditTrickType::class, $trickModel);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->updateTrickFromDto($trick, $trickModel, $form);

            $this->entityManager->flush();
            $this->addFlash('success', 'Le trick a bien été modifié');

            return $this->redirectToRoute('trick_show', ["category_slug" => $trick->getTrickCategory()->getSlug(), "slug" => $trick->getSlug()]);
        }

        return $this->renderForm('trick/edit.html.twig', compact('form', 'trick'));
    }

    #[Route('/trick/{slug}/delete', name: 'trick_delete', methods: ['DELETE'])]
    public function delete(Request $request, Trick $trick): Response
    {

        $this->denyAccessUnlessGranted('CAN_DELETE', $trick, "Vous n'avez pas le droit d'acceder à ce trick");

        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid("delete-trick", $data['_token']))
        {
            foreach($trick->getTrickImages() as $trickImage)
            {
                $this->imageService->removeUploadedTrickImage($trickImage);
            }
            $this->entityManager->remove($trick);
            $this->entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'success'
            ]);
            //        return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->json([
            'code' => 400,
            'message' => 'error'
        ], 400);
    }

//    #[Route('/trick/{slug}/edit-tab', name: 'trick_edit-tab')]
//    public function changeDivContent(string $slug, Request $request): Response
//    {
//        $data = json_decode($request->getContent(), true);
//        dd($data);
//        return $this->json([
//
//        ]);
//    }

    private function updateTrickFromDto(Trick $trick, EditTrickFormModel $trickModel, FormInterface $form){
        $trick->setName($trickModel->name);
        $trick->setDescription($trickModel->description);
        $trick->setTrickCategory($trickModel->trickCategory);
        $trick->setUpdatedAt(new \DateTime('now'));

        $newTrickImagesForm = $form->get('newTrickImages')->getData();
        foreach($newTrickImagesForm as $newTrickImage)
        {
            if($newTrickImage && !$newTrickImage->getId()){
                $trick->addTrickImage($newTrickImage);
                $this->imageService->moveTrickImageToFinalDirectory($newTrickImage);
            }
        }

        $newTrickVideosForm = $form->get('newTrickVideos')->getData();
        foreach($newTrickVideosForm as $newTrickVideo)
        {
            if($newTrickVideo){
                $trick->addTrickVideo($newTrickVideo);
            }
        }
    }
}
