<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Receiver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class ReceiverRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Receiver::class));
    }

    public function findRandomPaged(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('r')
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->execute()
            ;
    }

    public function findPaged(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->execute()
        ;
    }
}
