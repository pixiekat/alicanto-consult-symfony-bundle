<?php

declare(strict_types=1);

namespace Pixiekat\AlicantoConsult;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722043006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add groups table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consult_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, group_email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consult_group_preferences (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, is_private TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_7B714C55FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consult_group_preferences ADD CONSTRAINT FK_7B714C55FE54D947 FOREIGN KEY (group_id) REFERENCES consult_groups (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE consult_group_preferences');
        $this->addSql('DROP TABLE consult_groups');
    }
}
