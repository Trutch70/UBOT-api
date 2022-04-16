<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220416234317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE industry (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiver ADD industry_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receiver ADD CONSTRAINT FK_3DB88C962B19A734 FOREIGN KEY (industry_id) REFERENCES industry (id)');
        $this->addSql('CREATE INDEX IDX_3DB88C962B19A734 ON receiver (industry_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE receiver DROP FOREIGN KEY FK_3DB88C962B19A734');
        $this->addSql('DROP TABLE industry');
        $this->addSql('DROP INDEX IDX_3DB88C962B19A734 ON receiver');
        $this->addSql('ALTER TABLE receiver DROP industry_id');
    }
}
