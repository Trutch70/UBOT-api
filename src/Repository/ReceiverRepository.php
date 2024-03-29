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

    public function createOrderedQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        return $this->_em->createQueryBuilder()
            ->select($alias)
            ->from($this->_entityName, $alias, $indexBy)
            ->addSelect(sprintf('CASE WHEN %s.position IS NULL THEN 1 ELSE 0 END as HIDDEN position_is_null', $alias))
            ->addOrderBy('position_is_null', 'ASC')
            ->addOrderBy(sprintf('%s.position', $alias), 'ASC')
            ->addOrderBy(sprintf('%s.id', $alias), 'ASC')
        ;
    }

    public function findPaged(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createOrderedQueryBuilder('r')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * @return array|Receiver[]
     */
    public function findByPositionInBetween(?int $start, ?int $end): array
    {
        $qb = $this->createQueryBuilder('r');

        if (null !== $start) {
            $qb->andWhere('r.position > :start');
            $qb->setParameter('start', $start);
        }

        if (null !== $end) {
            $qb->andWhere('r.position < :end');
            $qb->setParameter('end', $end);
        }

        return $qb->getQuery()->execute();
    }
}
