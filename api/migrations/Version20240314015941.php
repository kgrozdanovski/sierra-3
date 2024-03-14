<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240314015941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create access_token and access_token_audit tables';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('access_token')) {
            $this->addSql(
                'CREATE TABLE "access_token" (
                    id UUID NOT NULL,
                    user_id UUID NOT NULL,
                    token VARCHAR(56) NOT NULL,
                    valid_until TIMESTAMP(0),
                    created_by UUID NOT NULL,
                    created_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    modified_by UUID NOT NULL,
                    modified_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    deleted BOOLEAN NOT NULL DEFAULT FALSE,
                    PRIMARY KEY(id)
                )'
            );
        }

        if (!$schema->hasTable('access_token_audit')) {
            $this->addSql(
                'CREATE TABLE "access_token_audit" (
                    id UUID NOT NULL,
                    request_id UUID NOT NULL,
                    entity_id UUID NOT NULL,
                    property_name VARCHAR(128) NOT NULL,
                    original_value_string VARCHAR(256) DEFAULT NULL,
                    updated_value_string VARCHAR(256) NOT NULL,
                    created_by UUID NOT NULL,
                    created_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    PRIMARY KEY(id)
                )'
            );
        }

        // Add foreign keys
        $this->addSql('ALTER TABLE "access_token" ADD CONSTRAINT FK_ACCESS_TOKEN_USER_ID
                FOREIGN KEY (user_id)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "access_token" ADD CONSTRAINT FK_ACCESS_TOKEN_CREATED_BY
                FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "access_token" ADD CONSTRAINT FK_ACCESS_TOKEN_MODIFIED_BY
                FOREIGN KEY (modified_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `user_audit` table foreign keys
        $this->addSql('ALTER TABLE "access_token_audit" ADD CONSTRAINT FK_ACCESS_TOKEN_AUDIT_CREATED_BY
                FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "access_token_audit" ADD CONSTRAINT FK_ACCESS_TOKEN_AUDIT_ENTITY_ID
                FOREIGN KEY (entity_id)
            REFERENCES "access_token" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('access_token_audit')) {
            $this->addSql('DROP TABLE "access_token_audit"');
        }

        if ($schema->hasTable('access_token')) {
            $this->addSql('DROP TABLE "access_token"');
        }
    }
}
