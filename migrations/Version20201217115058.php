<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217115058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C734413DD');
        $this->addSql('ALTER TABLE commandes_products DROP FOREIGN KEY FK_D817A6268BF5C2E6');
        $this->addSql('DROP TABLE commande_products');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_products');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_products (id INT AUTO_INCREMENT NOT NULL, commande_product_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_659A42C0DD464ABC (commande_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, cmd_id INT DEFAULT NULL, total INT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX IDX_35D4282C734413DD (cmd_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commandes_products (commandes_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_D817A6268BF5C2E6 (commandes_id), INDEX IDX_D817A6266C8A81A9 (products_id), PRIMARY KEY(commandes_id, products_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande_products ADD CONSTRAINT FK_659A42C0DD464ABC FOREIGN KEY (commande_product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C734413DD FOREIGN KEY (cmd_id) REFERENCES commande_products (id)');
        $this->addSql('ALTER TABLE commandes_products ADD CONSTRAINT FK_D817A6266C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_products ADD CONSTRAINT FK_D817A6268BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
    }
}
