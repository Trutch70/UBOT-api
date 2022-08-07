<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807131745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiver DROP FOREIGN KEY FK_3DB88C9664D218E');
        $this->addSql('DROP INDEX IDX_3DB88C9664D218E ON receiver');
        $this->addSql('ALTER TABLE receiver DROP location_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE receiver ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receiver ADD CONSTRAINT FK_3DB88C9664D218E FOREIGN KEY (location_id) REFERENCES location (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3DB88C9664D218E ON receiver (location_id)');
    }
}
