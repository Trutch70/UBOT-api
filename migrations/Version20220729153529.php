<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\MigrationWithManagerRegistryInterface;
use App\Entity\Receiver;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ManagerRegistry;

final class Version20220729153529 extends AbstractMigration implements MigrationWithManagerRegistryInterface
{
    private ManagerRegistry $managerRegistry;

    public function getDescription(): string
    {
        return 'Add images array to the receiver table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE receiver ADD images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\'');
    }

    public function postUp(Schema $schema): void
    {
        $receivers = $this->getManagerRegistry()->getRepository(Receiver::class)->findAll();

        foreach ($receivers as $receiver) {
            if (!$receiver->getImagePath()) {
                continue;
            }

            $receiver->addImage($receiver->getImagePath());
        }

        $this->getManagerRegistry()->getManager()->flush();
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE receiver DROP images');
    }

    public function getManagerRegistry(): ManagerRegistry
    {
        return $this->managerRegistry;
    }

    public function setManagerRegistry(ManagerRegistry $registry): void
    {
        $this->managerRegistry = $registry;
    }
}
