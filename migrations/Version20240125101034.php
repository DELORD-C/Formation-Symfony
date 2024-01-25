<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240125101034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE postCommentLike (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, comment_id INTEGER NOT NULL, CONSTRAINT FK_6807C029A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6807C029F8697D13 FOREIGN KEY (comment_id) REFERENCES postComment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6807C029A76ED395 ON postCommentLike (user_id)');
        $this->addSql('CREATE INDEX IDX_6807C029F8697D13 ON postCommentLike (comment_id)');
        $this->addSql('CREATE TABLE reviewCommentLike (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, comment_id INTEGER NOT NULL, CONSTRAINT FK_3CEF08E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3CEF08E5F8697D13 FOREIGN KEY (comment_id) REFERENCES reviewComment (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3CEF08E5A76ED395 ON reviewCommentLike (user_id)');
        $this->addSql('CREATE INDEX IDX_3CEF08E5F8697D13 ON reviewCommentLike (comment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE postCommentLike');
        $this->addSql('DROP TABLE reviewCommentLike');
    }
}
