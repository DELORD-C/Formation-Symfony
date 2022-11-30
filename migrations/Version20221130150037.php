<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130150037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD refere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496E21EA8 FOREIGN KEY (refere_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496E21EA8 ON user (refere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496E21EA8');
        $this->addSql('DROP INDEX IDX_8D93D6496E21EA8 ON user');
        $this->addSql('ALTER TABLE user DROP refere_id');
    }
}
