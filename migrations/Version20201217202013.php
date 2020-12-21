<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217202013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_quantity (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_49D30EACF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_quantity ADD CONSTRAINT FK_49D30EACF347EFB FOREIGN KEY (produit_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE commandes ADD cmd_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CF34EA73B FOREIGN KEY (cmd_id_id) REFERENCES commande_quantity (id)');
        $this->addSql('CREATE INDEX IDX_35D4282CF34EA73B ON commandes (cmd_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CF34EA73B');
        $this->addSql('DROP TABLE commande_quantity');
        $this->addSql('DROP INDEX IDX_35D4282CF34EA73B ON commandes');
        $this->addSql('ALTER TABLE commandes DROP cmd_id_id');
    }
}
