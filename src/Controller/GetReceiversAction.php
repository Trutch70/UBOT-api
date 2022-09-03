<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ReceiverRepository;
use Symfony\Component\HttpFoundation\Request;

class GetReceiversAction
{
    private ReceiverRepository $repository;

    public function __construct(ReceiverRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request): array
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');
        $random = $request->query->get('rand') ?? true;

        return $random ?
            $this->repository->findRandomPaged(
                $page ? (int)$page : 1,
                $limit ? (int)$limit : 30,
            ) :
            $this->repository->findPaged(
                $page ? (int)$page : 1,
                $limit ? (int)$limit : 30,
            )
        ;
    }
}
