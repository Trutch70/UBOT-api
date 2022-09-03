<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Receiver;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

class ReceiverRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata(Receiver::class));
    }

    public function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->select($alias)
            ->from($this->_entityName, $alias, $indexBy)
            ->addSelect(sprintf('CASE WHEN %s.order IS NULL THEN 1 ELSE 0 END as HIDDEN order_is_null', $alias))
            ->addOrderBy('order_is_null', 'ASC')
            ->addOrderBy(sprintf('%s.order', $alias), 'ASC')
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
