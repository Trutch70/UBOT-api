<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\{DBAL\Schema\Schema, Migrations\AbstractMigration};

final class Version20220903181654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE receiver ADD `order` INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE receiver DROP `order`');
    }
}
