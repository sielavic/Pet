<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240712174854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks ADD COLUMN parent_id int  NULL;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks DROP COLUMN parent_id;');
    }
}
