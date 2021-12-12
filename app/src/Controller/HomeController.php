<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private TrickRepository $trickRepository;
    private RequestStack $requestStack;
    private Paginator $paginator;

    public function __construct(TrickRepository $trickRepository, RequestStack $requestStack, Paginator $paginator)
    {
        $this->trickRepository = $trickRepository;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        // check whether request is called directly via route or via Twig template
        $isMainRequest = $this->requestStack->getMainRequest() === $request;

        $tricks = $this->trickRepository->findBy([], ['createdAt' => 'ASC'], 10, 0);

        if ($request->isXmlHttpRequest() || !$isMainRequest) {
            $data = json_decode($request->getContent(), true);

            $paginatedTricks = $this->paginator->loadMoreTricks($currentPage = $request->query->get('page'));
            [
                $tricks,
                $totalPages,
                $currentPage
            ] = $paginatedTricks;

            return $this->json([
                '_template' => $this->render('trick/_list.html.twig', compact('tricks', 'totalPages', 'currentPage')),
                'nextPage' => $currentPage + 1 <= $totalPages ? $currentPage + 1 : false
            ], Response::HTTP_OK);
        }

        return $this->render('home/index.html.twig', compact('tricks'));
    }
}
