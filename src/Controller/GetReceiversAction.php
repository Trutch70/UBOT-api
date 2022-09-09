<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ReceiverRepository;
use Symfony\Component\HttpFoundation\Request;

class GetReceiversAction
{
    public function __construct(private ReceiverRepository $repository) {}

    public function __invoke(Request $request): array
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        return $this->repository->findPaged(
            $page ? (int)$page : 1,
            $limit ? (int)$limit : 30,
        );
    }
}
