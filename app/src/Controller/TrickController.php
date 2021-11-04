<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    private TrickRepository $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    #[Route('/{category_slug}/{slug}', name: 'trick_show')]
    public function show(string $slug): Response
    {
        $trick = $this->trickRepository->findOneBy(compact('slug'));

        return $this->render('trick/index.html.twig', compact('trick'));
    }
}
