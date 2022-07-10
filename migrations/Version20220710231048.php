<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Doctrine\MigrationWithManagerRegistryInterface;
use App\Entity\Link;
use App\Entity\Receiver;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220710231048 extends AbstractMigration implements MigrationWithManagerRegistryInterface
{
    private ManagerRegistry $managerRegistry;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, receiver_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_36AC99F1CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id)');
    }

    public function postUp(Schema $schema): void
    {
        $receivers = $this->getManagerRegistry()->getRepository(Receiver::class)->findAll();

        foreach ($receivers as $receiver) {
            if ($receiver->getInstagram()) {
                $link = new Link();
                $link->setReceiver($receiver);
                $link->setName('Instagram');
                $link->setUrl($receiver->getInstagram());

                $receiver->addLink($link);
                $this->getManagerRegistry()->getManager()->persist($link);
            }

            if ($receiver->getWebPage()) {
                $link = new Link();
                $link->setReceiver($receiver);
                $link->setName('Website');
                $link->setUrl($receiver->getWebPage());

                $receiver->addLink($link);
                $this->getManagerRegistry()->getManager()->persist($link);
            }
        }

        $this->getManagerRegistry()->getManager()->flush();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE link');
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
