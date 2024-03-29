<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;

class MigrationFactoryDecorator implements MigrationFactory
{
    public function __construct(
        private MigrationFactory $migrationFactory,
        private ManagerRegistry $managerRegistry
    ) {}

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $instance = $this->migrationFactory->createVersion($migrationClassName);

        if ($instance instanceof MigrationWithManagerRegistryInterface) {
            $instance->setManagerRegistry($this->managerRegistry);
        }

        return $instance;
    }
}
