<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124110333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__postComment AS SELECT id, post_id, body, created_at FROM postComment');
        $this->addSql('DROP TABLE postComment');
        $this->addSql('CREATE TABLE postComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER NOT NULL, user_id INTEGER NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_92D868164B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_92D86816A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postComment (id, post_id, body, created_at) SELECT id, post_id, body, created_at FROM __temp__postComment');
        $this->addSql('DROP TABLE __temp__postComment');
        $this->addSql('CREATE INDEX IDX_92D868164B89032C ON postComment (post_id)');
        $this->addSql('CREATE INDEX IDX_92D86816A76ED395 ON postComment (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reviewComment AS SELECT id, review_id, body, created_at FROM reviewComment');
        $this->addSql('DROP TABLE reviewComment');
        $this->addSql('CREATE TABLE reviewComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, review_id INTEGER NOT NULL, user_id INTEGER NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_288CCBB33E2E969B FOREIGN KEY (review_id) REFERENCES review (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_288CCBB3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reviewComment (id, review_id, body, created_at) SELECT id, review_id, body, created_at FROM __temp__reviewComment');
        $this->addSql('DROP TABLE __temp__reviewComment');
        $this->addSql('CREATE INDEX IDX_288CCBB33E2E969B ON reviewComment (review_id)');
        $this->addSql('CREATE INDEX IDX_288CCBB3A76ED395 ON reviewComment (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__postComment AS SELECT id, post_id, body, created_at FROM postComment');
        $this->addSql('DROP TABLE postComment');
        $this->addSql('CREATE TABLE postComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_92D868164B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postComment (id, post_id, body, created_at) SELECT id, post_id, body, created_at FROM __temp__postComment');
        $this->addSql('DROP TABLE __temp__postComment');
        $this->addSql('CREATE INDEX IDX_92D868164B89032C ON postComment (post_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reviewComment AS SELECT id, review_id, body, created_at FROM reviewComment');
        $this->addSql('DROP TABLE reviewComment');
        $this->addSql('CREATE TABLE reviewComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, review_id INTEGER NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_288CCBB33E2E969B FOREIGN KEY (review_id) REFERENCES review (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reviewComment (id, review_id, body, created_at) SELECT id, review_id, body, created_at FROM __temp__reviewComment');
        $this->addSql('DROP TABLE __temp__reviewComment');
        $this->addSql('CREATE INDEX IDX_288CCBB33E2E969B ON reviewComment (review_id)');
    }
}
