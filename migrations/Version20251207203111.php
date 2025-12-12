<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251207203111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key to payment.user_id referencing user.id';
    }

    public function up(Schema $schema): void
    {
        // 1. Ensure no NULL values exist (otherwise MySQL will fail)
        $this->addSql("UPDATE payment SET user_id = 1 WHERE user_id IS NULL");

        // 2. Make column NOT NULL
        $this->addSql('ALTER TABLE payment MODIFY user_id INT NOT NULL');

        // 3. Add foreign key
        $this->addSql('ALTER TABLE payment 
            ADD CONSTRAINT FK_PAYMENT_USER 
            FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_PAYMENT_USER');
        $this->addSql('ALTER TABLE payment MODIFY user_id INT DEFAULT NULL');
    }
}
