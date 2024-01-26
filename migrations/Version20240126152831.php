<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240126152831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, user_id, title, body, created_at FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO post (id, user_id, title, body, created_at) SELECT id, user_id, title, body, created_at FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__postComment AS SELECT id, post_id, user_id, body, created_at FROM postComment');
        $this->addSql('DROP TABLE postComment');
        $this->addSql('CREATE TABLE postComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_92D868164B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_92D86816A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postComment (id, post_id, user_id, body, created_at) SELECT id, post_id, user_id, body, created_at FROM __temp__postComment');
        $this->addSql('DROP TABLE __temp__postComment');
        $this->addSql('CREATE INDEX IDX_92D86816A76ED395 ON postComment (user_id)');
        $this->addSql('CREATE INDEX IDX_92D868164B89032C ON postComment (post_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__postCommentLike AS SELECT id, user_id, comment_id FROM postCommentLike');
        $this->addSql('DROP TABLE postCommentLike');
        $this->addSql('CREATE TABLE postCommentLike (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, comment_id INTEGER NOT NULL, CONSTRAINT FK_6807C029A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6807C029F8697D13 FOREIGN KEY (comment_id) REFERENCES postComment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postCommentLike (id, user_id, comment_id) SELECT id, user_id, comment_id FROM __temp__postCommentLike');
        $this->addSql('DROP TABLE __temp__postCommentLike');
        $this->addSql('CREATE INDEX IDX_6807C029F8697D13 ON postCommentLike (comment_id)');
        $this->addSql('CREATE INDEX IDX_6807C029A76ED395 ON postCommentLike (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, user_id, movie_title, body, rating, created_at FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, movie_title VARCHAR(255) NOT NULL, body CLOB NOT NULL, rating INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO review (id, user_id, movie_title, body, rating, created_at) SELECT id, user_id, movie_title, body, rating, created_at FROM __temp__review');
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__post AS SELECT id, user_id, title, body, created_at FROM post');
        $this->addSql('DROP TABLE post');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO post (id, user_id, title, body, created_at) SELECT id, user_id, title, body, created_at FROM __temp__post');
        $this->addSql('DROP TABLE __temp__post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__postComment AS SELECT id, post_id, user_id, body, created_at FROM postComment');
        $this->addSql('DROP TABLE postComment');
        $this->addSql('CREATE TABLE postComment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, post_id INTEGER NOT NULL, user_id INTEGER NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_92D868164B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_92D86816A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postComment (id, post_id, user_id, body, created_at) SELECT id, post_id, user_id, body, created_at FROM __temp__postComment');
        $this->addSql('DROP TABLE __temp__postComment');
        $this->addSql('CREATE INDEX IDX_92D868164B89032C ON postComment (post_id)');
        $this->addSql('CREATE INDEX IDX_92D86816A76ED395 ON postComment (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__postCommentLike AS SELECT id, user_id, comment_id FROM postCommentLike');
        $this->addSql('DROP TABLE postCommentLike');
        $this->addSql('CREATE TABLE postCommentLike (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, comment_id INTEGER NOT NULL, CONSTRAINT FK_6807C029A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6807C029F8697D13 FOREIGN KEY (comment_id) REFERENCES postComment (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO postCommentLike (id, user_id, comment_id) SELECT id, user_id, comment_id FROM __temp__postCommentLike');
        $this->addSql('DROP TABLE __temp__postCommentLike');
        $this->addSql('CREATE INDEX IDX_6807C029A76ED395 ON postCommentLike (user_id)');
        $this->addSql('CREATE INDEX IDX_6807C029F8697D13 ON postCommentLike (comment_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review AS SELECT id, user_id, movie_title, body, rating, created_at FROM review');
        $this->addSql('DROP TABLE review');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, movie_title VARCHAR(255) NOT NULL, body CLOB NOT NULL, rating INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO review (id, user_id, movie_title, body, rating, created_at) SELECT id, user_id, movie_title, body, rating, created_at FROM __temp__review');
        $this->addSql('DROP TABLE __temp__review');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
    }
}
