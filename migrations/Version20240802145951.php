<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240802145951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE labor_costs (id int(255)  AUTO_INCREMENT NOT NULL,user_id int(255) NOT NULL,task_id int(255) NOT NULL,title varchar(255) NOT NULL,
         hours int(100) DEFAULT NULL, date date DEFAULT NULL, created_at datetime DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE labor_costs ADD CONSTRAINT fk_hours_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE;');
        $this->addSql('ALTER TABLE labor_costs ADD CONSTRAINT fk_hours_task FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE labor_costs');
        $this->addSql('ALTER TABLE labor_costs DROP FOREIGN KEY fk_hours_user');
        $this->addSql('ALTER TABLE labor_costs DROP FOREIGN KEY fk_hours_task');
    }
}
