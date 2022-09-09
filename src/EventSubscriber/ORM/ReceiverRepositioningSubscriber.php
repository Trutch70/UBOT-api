<?php

declare(strict_types=1);

namespace App\EventSubscriber\ORM;

use App\Entity\Receiver;
use App\Service\Receiver\ReceiverRepositioningService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

class ReceiverRepositioningSubscriber implements EventSubscriberInterface
{
    public function __construct(private ReceiverRepositioningService $receiverRepositioningService) {}

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $inserted = false;
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Receiver || null === $entity->getPosition()) {
                continue;
            }
            $this->receiverRepositioningService->handlePositionInsertion($em, $entity->getPosition());
            $inserted = true;
        }

        if ($inserted) {
            return;
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Receiver) {
                continue;

            }
            $changeSet = $uow->getEntityChangeSet($entity);

            if (!isset($changeSet['position'])) {
                continue;
            }

            if ($changeSet['position'][0] === null) {
                $this->receiverRepositioningService->handlePositionInsertion($em, $changeSet['position'][1]);
                continue;
            }

            $this->receiverRepositioningService->handlePositionChange($em, $changeSet['position'][0], $changeSet['position'][1]);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }
}
