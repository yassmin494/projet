<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251208203439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY `FK_42C8495519EB6921`');
        $this->addSql('DROP INDEX IDX_42C8495519EB6921 ON reservation');
        $this->addSql('ALTER TABLE reservation ADD client_name VARCHAR(255) NOT NULL, ADD client_email VARCHAR(255) DEFAULT NULL, ADD start_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL, DROP date_reservation, DROP client_id');
        $this->addSql('ALTER TABLE service ADD name VARCHAR(255) NOT NULL, ADD image_filename VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, DROP title, DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD date_reservation DATE NOT NULL, ADD client_id INT NOT NULL, DROP client_name, DROP client_email, DROP start_date, DROP end_date');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT `FK_42C8495519EB6921` FOREIGN KEY (client_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_42C8495519EB6921 ON reservation (client_id)');
        $this->addSql('ALTER TABLE service ADD title VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, DROP name, DROP image_filename, DROP created_at');
    }
}
