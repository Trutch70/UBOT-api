<?php

declare(strict_types=1);

namespace App\Doctrine;

use Doctrine\Persistence\ManagerRegistry;

interface MigrationWithManagerRegistryInterface
{
    public function getManagerRegistry(): ManagerRegistry;

    public function setManagerRegistry(ManagerRegistry $registry): void;
}
