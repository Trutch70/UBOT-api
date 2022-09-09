<?php

declare(strict_types=1);

namespace App\Service\Receiver;

use App\Entity\Receiver;
use App\Repository\ReceiverRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReceiverRepositioningService
{
    public function __construct(private ReceiverRepository $repository) {}

    public function handlePositionInsertion(EntityManagerInterface $em, int $positionInsertedAt): void
    {
        $classMetadata = $em->getClassMetadata(Receiver::class);
        $uow = $em->getUnitOfWork();
        $receiversToReposition = $this->repository->findByPositionInBetween($positionInsertedAt - 1, null);

        foreach ($receiversToReposition as $receiver) {
            $receiver->setPosition($receiver->getPosition() + 1);
            $uow->computeChangeSet($classMetadata, $receiver);
        }
    }

    public function handlePositionChange(EntityManagerInterface $em, int $positionBefore, int $newPosition): void
    {
        if ($positionBefore === $newPosition) {
            return;
        }

        $classMetadata = $em->getClassMetadata(Receiver::class);
        $uow = $em->getUnitOfWork();

        if ($positionBefore > $newPosition) {
            $receiversToReposition = $this->repository->findByPositionInBetween($newPosition - 1, $positionBefore);

            foreach ($receiversToReposition as $receiver) {
                $receiver->setPosition($receiver->getPosition() + 1);
                $uow->computeChangeSet($classMetadata, $receiver);
            }

            return;
        }

        $receiversToReposition = $this->repository->findByPositionInBetween($positionBefore, $newPosition + 1);

        foreach ($receiversToReposition as $receiver) {
            $receiver->setPosition($receiver->getPosition() - 1);
            $uow->computeChangeSet($classMetadata, $receiver);
        }
    }
}
