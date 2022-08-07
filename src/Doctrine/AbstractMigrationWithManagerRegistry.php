<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractMigrationWithManagerRegistry extends AbstractMigration implements MigrationWithManagerRegistryInterface
{
    protected ManagerRegistry $registry;

    public function getManagerRegistry(): ManagerRegistry
    {
        return $this->registry;
    }

    public function setManagerRegistry(ManagerRegistry $registry): void
    {
        $this->registry = $registry;
    }
}
