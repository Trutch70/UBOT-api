<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\AbstractMigrationWithManagerRegistry;
use App\Entity\Receiver;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807131346 extends AbstractMigrationWithManagerRegistry
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receiver_location (receiver_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_663B723ECD53EDB6 (receiver_id), INDEX IDX_663B723E64D218E (location_id), PRIMARY KEY(receiver_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE receiver_location ADD CONSTRAINT FK_663B723ECD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receiver_location ADD CONSTRAINT FK_663B723E64D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE receiver_location');
    }

    public function postUp(Schema $schema): void
    {
        $receivers = $this->getManagerRegistry()->getRepository(Receiver::class)->findAll();

        /** @var Receiver $receiver */
        foreach ($receivers as $receiver) {
            $receiver->setLocations(new ArrayCollection([$receiver->getLocation()]));
            $this->getManagerRegistry()->getManager()->persist($receiver);
        }
        $this->getManagerRegistry()->getManager()->flush();
    }
}
