<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200323235256 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_detail ADD product_order_id INT NOT NULL, ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46462F07AF FOREIGN KEY (product_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_ED896F46462F07AF ON order_detail (product_order_id)');
        $this->addSql('CREATE INDEX IDX_ED896F464584665A ON order_detail (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46462F07AF');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F464584665A');
        $this->addSql('DROP INDEX IDX_ED896F46462F07AF ON order_detail');
        $this->addSql('DROP INDEX IDX_ED896F464584665A ON order_detail');
        $this->addSql('ALTER TABLE order_detail DROP product_order_id, DROP product_id');
    }
}
