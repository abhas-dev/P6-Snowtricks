<?php

namespace App\Service;

use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    private RequestStack $requestStack;
    private MessageRepository $messageRepository;

    public function __construct(RequestStack $requestStack, MessageRepository $messageRepository)
    {
        $this->requestStack = $requestStack;
        $this->messageRepository = $messageRepository;
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
}