<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

//    #[Route('/{offset?}', name: 'homepage')]
//    public function index(): Response
//    {
//        $request = $this->requestStack->getCurrentRequest();
//        // check whether request is called directly via route or via Twig template
//        $isMainRequest = $this->requestStack->getMainRequest() === $request;
//        $tricks = $this->trickRepository->findBy([], ['createdAt' => 'ASC'], 10, 0);
//
////        $request->isXmlHttpRequest() || !$isMainRequest ? $template = 'trick/_list.html.twig' : $template = 'home/index.html.twig';
//        $offsetValue = 1;
//        $paginatedTricks = $this->paginator->loadMoreTricks($offset = $offsetValue);
//        [
//            $tricks,
//            $totalPages,
//            $currentPage
//        ] = $paginatedTricks;
//
//        if($request->isXmlHttpRequest() || !$isMainRequest)
//        {
//            $template = 'trick/_list.html.twig';
//            $offsetValue = $request->attributes->get('offset');
//
//            $response = $this->render($template, compact('tricks', 'totalPages', 'currentPage'));
//            return $this->json($response);
//
//            return $this->json([
//                '_template' => $this->renderView()
//            ])
//        }
//
//        $template = 'home/index.html.twig';
//
//        return $this->render($template, compact('tricks', 'totalPages', 'currentPage'));
//    }

    #[Route('/{offset?}', name: 'homepage')]
    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        // check whether request is called directly via route or via Twig template
        $isMainRequest = $this->requestStack->getMainRequest() === $request;

        $tricks = $this->trickRepository->findBy([], ['createdAt' => 'ASC'], 10, 0);

        if($request->isXmlHttpRequest() || !$isMainRequest)
        {
            $data = json_decode($request->getContent(), true);

//            return $this->json([
//                'tricks' => $this->trickRepository->getArrayTricks($offset = $request->attributes->get('offset')),
//                'totalTricks' => $this->trickRepository->getCountTricks()
//            ]);
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

//    #[Route('/', name: 'homepage')]
//    public function index(): Response
//    {
////        $tricks = $this->trickRepository->findAll();
////        $first10Tricks = $trickRepository->findBy(['id'], ['createdAt' => 'ASC'], 10, 0);
//
////        dd($tricks);
//
//        $request = $this->requestStack->getCurrentRequest();
//        // check whether request is called directly via route or via Twig template
//        $isMainRequest = $this->requestStack->getMainRequest() === $request;
//
//        $request->isXmlHttpRequest() || !$isMainRequest ? $template = 'ajax_test.html.twig' : $template = 'home/index.html.twig';
//
//        $paginatedTricks = $this->paginator->loadMoreTricks();
//        [
//            $tricks,
//            $totalPages,
//            $currentPage
//        ] = $paginatedTricks;
//
//        // TODO
////        $requestPage = $request->get(self::PAGE_QUERY_PARAMETER);
////        $page = $requestPage === null ? 1 : (int)$requestPage;
//
//
////        return $this->render($template, compact('tricks'));
//
//        return $this->json([
//            'tricks' => $this->trickRepository->getTricks(),
//            'totalTricks' => $this->trickRepository->getCountTricks()
//        ]);
//    }

//    private function load(int $lastTrickId, int $limit)
//    {
//        $totalPages = ceil($this->trickRepository->findTotal() / $limit);
//        $currentPage = $this->requestStack->getCurrentRequest()->query->get('page', 1);
//
//    }
}

//if($request->isXmlHttpRequest() || !$isMainRequest)
//{
//    $template = 'test.html.twig';
//    $paginatedTricks = $this->paginator->loadMoreTricks();
//    [
//        $tricks,
//        $totalPages,
//        $currentPage
//    ] = $paginatedTricks;
//
//    return $this->json([
//        'code' => 200,
//        'message' => 'sucess',
//    ]);
//}