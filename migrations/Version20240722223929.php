<?php

declare(strict_types=1);

namespace Pixiekat\AlicantoConsult;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722223929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add group members table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consult_group_members (group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E9A51698FE54D947 (group_id), INDEX IDX_E9A51698A76ED395 (user_id), PRIMARY KEY(group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consult_group_members ADD CONSTRAINT FK_E9A51698FE54D947 FOREIGN KEY (group_id) REFERENCES consult_groups (id)');
        $this->addSql('ALTER TABLE consult_group_members ADD CONSTRAINT FK_E9A51698A76ED395 FOREIGN KEY (user_id) REFERENCES consult_users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consult_group_members DROP FOREIGN KEY FK_E9A51698FE54D947');
        $this->addSql('ALTER TABLE consult_group_members DROP FOREIGN KEY FK_E9A51698A76ED395');
        $this->addSql('DROP TABLE consult_group_members');
    }
}
