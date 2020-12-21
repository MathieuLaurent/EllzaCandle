<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217113338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_products (id INT AUTO_INCREMENT NOT NULL, commande_product_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_659A42C0DD464ABC (commande_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_products ADD CONSTRAINT FK_659A42C0DD464ABC FOREIGN KEY (commande_product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE commandes ADD cmd_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C734413DD FOREIGN KEY (cmd_id) REFERENCES commande_products (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C734413DD ON commandes (cmd_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C734413DD');
        $this->addSql('DROP TABLE commande_products');
        $this->addSql('DROP INDEX IDX_35D4282C734413DD ON commandes');
        $this->addSql('ALTER TABLE commandes DROP cmd_id');
    }
}
