<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414155123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere ADD id_panier_global DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE enchere_fournisseur DROP FOREIGN KEY FK_6157411C4D81EE2C');
        $this->addSql('DROP INDEX IDX_6157411C4D81EE2C ON enchere_fournisseur');
        $this->addSql('ALTER TABLE enchere_fournisseur CHANGE idEnchere id_enchere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enchere_fournisseur ADD CONSTRAINT FK_6157411C4D81EE2C FOREIGN KEY (id_enchere_id) REFERENCES enchere (id)');
        $this->addSql('CREATE INDEX IDX_6157411C4D81EE2C ON enchere_fournisseur (id_enchere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enchere DROP id_panier_global');
        $this->addSql('ALTER TABLE enchere_fournisseur DROP FOREIGN KEY FK_6157411C4D81EE2C');
        $this->addSql('DROP INDEX IDX_6157411C4D81EE2C ON enchere_fournisseur');
        $this->addSql('ALTER TABLE enchere_fournisseur CHANGE id_enchere_id idEnchere INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enchere_fournisseur ADD CONSTRAINT FK_6157411C4D81EE2C FOREIGN KEY (idEnchere) REFERENCES enchere (id)');
        $this->addSql('CREATE INDEX IDX_6157411C4D81EE2C ON enchere_fournisseur (idEnchere)');
    }
}
