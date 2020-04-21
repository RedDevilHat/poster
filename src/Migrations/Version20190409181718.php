<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190409181718 extends AbstractMigration
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('DROP INDEX IDX_628A1EBFFE54D947');
        $this->addSql('DROP INDEX IDX_628A1EBFA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users_user_groups AS SELECT user_id, group_id FROM users_user_groups');
        $this->addSql('DROP TABLE users_user_groups');
        $this->addSql('CREATE TABLE users_user_groups (user_id INTEGER NOT NULL, group_id INTEGER NOT NULL, PRIMARY KEY(user_id, group_id), CONSTRAINT FK_628A1EBFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_628A1EBFFE54D947 FOREIGN KEY (group_id) REFERENCES user_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO users_user_groups (user_id, group_id) SELECT user_id, group_id FROM __temp__users_user_groups');
        $this->addSql('DROP TABLE __temp__users_user_groups');
        $this->addSql('CREATE INDEX IDX_628A1EBFFE54D947 ON users_user_groups (group_id)');
        $this->addSql('CREATE INDEX IDX_628A1EBFA76ED395 ON users_user_groups (user_id)');
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP INDEX IDX_628A1EBFA76ED395');
        $this->addSql('DROP INDEX IDX_628A1EBFFE54D947');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users_user_groups AS SELECT user_id, group_id FROM users_user_groups');
        $this->addSql('DROP TABLE users_user_groups');
        $this->addSql('CREATE TABLE users_user_groups (user_id INTEGER NOT NULL, group_id INTEGER NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('INSERT INTO users_user_groups (user_id, group_id) SELECT user_id, group_id FROM __temp__users_user_groups');
        $this->addSql('DROP TABLE __temp__users_user_groups');
        $this->addSql('CREATE INDEX IDX_628A1EBFA76ED395 ON users_user_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_628A1EBFFE54D947 ON users_user_groups (group_id)');
    }
}
