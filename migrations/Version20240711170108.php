<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711170108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT fk_task_assigneduser FOREIGN KEY (assigned_user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE;');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY fk_task_assigneduser');
    }
}
