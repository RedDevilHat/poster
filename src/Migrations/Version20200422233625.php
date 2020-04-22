<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200422233625 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE oauth2_access_tokens (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INTEGER DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D247A21B5F37A13B ON oauth2_access_tokens (token)');
        $this->addSql('CREATE INDEX IDX_D247A21B19EB6921 ON oauth2_access_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_D247A21BA76ED395 ON oauth2_access_tokens (user_id)');
        $this->addSql('CREATE TABLE oauth2_clients (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris CLOB NOT NULL --(DC2Type:array)
        , secret VARCHAR(255) NOT NULL, allowed_grant_types CLOB NOT NULL --(DC2Type:array)
        , name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_F9D02AE6A76ED395 ON oauth2_clients (user_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE TABLE users_user_groups (user_id INTEGER NOT NULL, group_id INTEGER NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_628A1EBFA76ED395 ON users_user_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_628A1EBFFE54D947 ON users_user_groups (group_id)');
        $this->addSql('CREATE TABLE oauth2_refresh_tokens (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INTEGER DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D394478C5F37A13B ON oauth2_refresh_tokens (token)');
        $this->addSql('CREATE INDEX IDX_D394478C19EB6921 ON oauth2_refresh_tokens (client_id)');
        $this->addSql('CREATE INDEX IDX_D394478CA76ED395 ON oauth2_refresh_tokens (user_id)');
        $this->addSql('CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE oauth2_auth_codes (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, user_id INTEGER DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri CLOB NOT NULL, expires_at INTEGER DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A018A10D5F37A13B ON oauth2_auth_codes (token)');
        $this->addSql('CREATE INDEX IDX_A018A10D19EB6921 ON oauth2_auth_codes (client_id)');
        $this->addSql('CREATE INDEX IDX_A018A10DA76ED395 ON oauth2_auth_codes (user_id)');
        $this->addSql('CREATE TABLE user_groups (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_953F224D5E237E06 ON user_groups (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE oauth2_access_tokens');
        $this->addSql('DROP TABLE oauth2_clients');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_user_groups');
        $this->addSql('DROP TABLE oauth2_refresh_tokens');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE oauth2_auth_codes');
        $this->addSql('DROP TABLE user_groups');
    }
}
