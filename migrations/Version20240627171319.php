<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627171319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tasks (id int(255)  AUTO_INCREMENT NOT NULL,user_id int(255) NOT NULL,assigned_user_id int(255) DEFAULT NULL,title varchar(255) DEFAULT NULL,content text DEFAULT NULL,
         priority varchar(20) DEFAULT NULL,hours int(100) DEFAULT NULL,state varchar(20) DEFAULT NULL,created_at datetime DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE tasks');
    }
}
