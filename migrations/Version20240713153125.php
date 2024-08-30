<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240713153125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT fk_task_project FOREIGN KEY (project_id) REFERENCES project(id) ON DELETE CASCADE ON UPDATE CASCADE;');
        $this->addSql('ALTER TABLE user_project_relation  DROP COLUMN projectid;');
        $this->addSql('ALTER TABLE user_project_relation  ADD COLUMN project_id int NOT NULL;');
        $this->addSql('ALTER TABLE user_project_relation  DROP COLUMN userid;');
        $this->addSql('ALTER TABLE user_project_relation  ADD COLUMN user_id int NOT NULL;');
        $this->addSql('ALTER TABLE user_project_relation ADD CONSTRAINT fk_relation_project FOREIGN KEY (project_id) REFERENCES project(id) ON DELETE CASCADE ON UPDATE CASCADE;');
        $this->addSql('ALTER TABLE user_project_relation ADD CONSTRAINT fk_relation_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE;');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
