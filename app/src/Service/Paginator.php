<?php

namespace App\Service;

use App\Repository\MessageRepository;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    private RequestStack $requestStack;
    private MessageRepository $messageRepository;
    private TrickRepository $trickRepository;

    public function __construct(RequestStack $requestStack, MessageRepository $messageRepository, TrickRepository $trickRepository)
    {
        $this->requestStack = $requestStack;
        $this->messageRepository = $messageRepository;
        $this->trickRepository = $trickRepository;
    }

    public function paginate(int $limit, int $trickId): array
    {
        $totalPages = ceil($this->messageRepository->findTotalByTrick($trickId) / $limit);
        $currentPage = $this->requestStack->getCurrentRequest()->query->get('page', 1);
        if(!is_int($currentPage) && $currentPage < 1 || $currentPage > $totalPages)
        {
            $currentPage = 1;
        }

        $offsetValue = ($currentPage - 1) * $limit;
        $messages = $this->messageRepository->findBy(['trick' => $trickId], ['createdAt' => 'DESC'], $limit, $offsetValue);

        return [$messages, $totalPages, $currentPage];
    }

    public function loadMoreTricks(int $lastTrick = 0, int $limit = 10): array
    {
        $totalPages = ceil($this->trickRepository->findTotal() / $limit);
        $currentPage = $this->requestStack->getCurrentRequest()->query->get('page', 1);
        if(!is_int($currentPage) && $currentPage < 1 || $currentPage > $totalPages)
        {
            $currentPage = 1;
        }

//        $offsetValue = ($currentPage - 1) * $limit;
        $offsetValue = $lastTrick;
        $tricks = $this->trickRepository->findBy([], ['createdAt' => 'DESC'], $limit, $offsetValue);

        return [$tricks, $totalPages, $currentPage];
    }
}