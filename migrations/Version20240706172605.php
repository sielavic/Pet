<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240706172605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks ADD file VARCHAR (255) COMMENT "Файл задачи"');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks DROP COLUMN file');

    }
}
