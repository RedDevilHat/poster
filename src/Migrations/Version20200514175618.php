<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200514175618 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE TABLE users_user_groups (user_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , group_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_628A1EBFA76ED395 ON users_user_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_628A1EBFFE54D947 ON users_user_groups (group_id)');
        $this->addSql('CREATE TABLE posts (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , title VARCHAR(255) NOT NULL, content CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_groups (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , name VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_953F224D5E237E06 ON user_groups (name)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_user_groups');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE user_groups');
    }
}
