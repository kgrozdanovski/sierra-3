<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230311020846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create essential user structure';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable("users")) {
            $this->addSql(
                'CREATE TABLE "users" (
                    id UUID NOT NULL,
                    email VARCHAR(180) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    created_by UUID NOT NULL,
                    created_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    modified_by UUID NOT NULL,
                    modified_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    deleted BOOLEAN NOT NULL DEFAULT FALSE,
                    PRIMARY KEY(id)
                )'
            );
        }

        if (!$schema->hasTable("user_audit")) {
            $this->addSql(
                'CREATE TABLE "user_audit" (
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

        if (!$schema->hasTable("role")) {
            $this->addSql(
                'CREATE TABLE "role" (
                    id UUID NOT NULL,
                    name VARCHAR(128) NOT NULL,
                    created_by UUID NOT NULL,
                    created_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    modified_by UUID NOT NULL,
                    modified_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    deleted BOOLEAN NOT NULL DEFAULT FALSE,
                    PRIMARY KEY(id)
                )'
            );
        }

        if (!$schema->hasTable("role_audit")) {
            $this->addSql(
                'CREATE TABLE "role_audit" (
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

        if (!$schema->hasTable("user_role")) {
            $this->addSql(
                'CREATE TABLE "user_role" (
                    id UUID NOT NULL,
                    user_id UUID NOT NULL,
                    role_id UUID NOT NULL,
                    created_by UUID NOT NULL,
                    created_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    modified_by UUID NOT NULL,
                    modified_at TIMESTAMP(0) NOT NULL DEFAULT now(),
                    deleted BOOLEAN NOT NULL DEFAULT FALSE,
                    PRIMARY KEY(id)
                )'
            );
        }

        if (!$schema->hasTable("user_role_audit")) {
            $this->addSql(
                'CREATE TABLE "user_role_audit" (
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

        // Add index for `email` column on `user` table
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON "users" (email)');

        // Add `user` table foreign keys
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_USER_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_USER_MODIFIED_BY FOREIGN KEY (modified_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `user_audit` table foreign keys
        $this->addSql('ALTER TABLE "user_audit" ADD CONSTRAINT FK_USER_AUDIT_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "user_audit" ADD CONSTRAINT FK_USER_AUDIT_ENTITY_ID FOREIGN KEY (entity_id)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `role` table foreign keys
        $this->addSql('ALTER TABLE "role" ADD CONSTRAINT FK_ROLE_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "role" ADD CONSTRAINT FK_ROLE_MODIFIED_BY FOREIGN KEY (modified_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `role_audit` table foreign keys
        $this->addSql('ALTER TABLE "role_audit" ADD CONSTRAINT FK_ROLE_AUDIT_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "role_audit" ADD CONSTRAINT FK_ROLE_AUDIT_ENTITY_ID FOREIGN KEY (entity_id)
            REFERENCES "role" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `user_role` table indexes
        $this->addSql('CREATE INDEX IDX_USER_ROLE_USER_ID ON user_role (user_id)');
        $this->addSql('CREATE INDEX IDX_USER_ROLE_ROLE_ID ON user_role (role_id)');

        // Add `user_role` table foreign keys
        $this->addSql('ALTER TABLE "user_role" ADD CONSTRAINT FK_USER_ROLE_USER_ID FOREIGN KEY (user_id)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "user_role" ADD CONSTRAINT FK_USER_ROLE_ROLE_ID FOREIGN KEY (role_id)
            REFERENCES "role" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "user_role" ADD CONSTRAINT FK_USER_ROLE_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "user_role" ADD CONSTRAINT FK_USER_ROLE_MODIFIED_BY FOREIGN KEY (modified_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Add `user_role_audit` table foreign keys
        $this->addSql('ALTER TABLE "user_role_audit" ADD CONSTRAINT FK_USER_ROLE_AUDIT_CREATED_BY FOREIGN KEY (created_by)
            REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE "user_role_audit" ADD CONSTRAINT FK_USER_ROLE_AUDIT_ENTITY_ID FOREIGN KEY (entity_id)
            REFERENCES "user_role" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );

        // Create default internal user
        $this->addSql('INSERT INTO "users" (id, email, password, created_by, modified_by) 
            VALUES (
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\',
                \'no-reply@sierra.local\',
                \'\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\'
            )'
        );

        // Create default roles
        $this->addSql('INSERT INTO "role" (id, name, created_by, modified_by) 
            VALUES (
                \'c24f318c-84eb-4a64-a9c5-ac4eeded1aa4\',
                \'admin\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\'
            )'
        );

        // Link default roles to default user
        $this->addSql('INSERT INTO "user_role" (id, user_id, role_id, created_by, modified_by) 
            VALUES (
                \'c24f318c-84eb-4a64-a9c5-ac4eeded1aa4\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\',
                \'c24f318c-84eb-4a64-a9c5-ac4eeded1aa4\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\',
                \'8d35b4fb-16f1-4d38-b592-580eaec56a99\'
            )'
        );
    }

    public function down(Schema $schema): void
    {
        // Drop `user_role_audit` table foreign keys
        if ($schema->getTable("user_role_audit")->hasForeignKey("FK_USER_ROLE_AUDIT_CREATED_BY")) {
            $this->addSql('ALTER TABLE "user_role_audit" DROP CONSTRAINT FK_USER_ROLE_AUDIT_CREATED_BY');
        }

        if ($schema->getTable("user_role_audit")->hasForeignKey("FK_USER_ROLE_AUDIT_ENTITY_ID")) {
            $this->addSql('ALTER TABLE "user_role_audit" DROP CONSTRAINT FK_USER_ROLE_AUDIT_ENTITY_ID');
        }

        // Drop `user_role` table foreign keys
        if ($schema->getTable("user_role")->hasForeignKey("FK_USER_ROLE_USER_ID")) {
            $this->addSql('ALTER TABLE "user_role" DROP CONSTRAINT FK_USER_ROLE_USER_ID');
        }

        if ($schema->getTable("user_role")->hasForeignKey("FK_USER_ROLE_ROLE_ID")) {
            $this->addSql('ALTER TABLE "user_role" DROP CONSTRAINT FK_USER_ROLE_ROLE_ID');
        }

        if ($schema->getTable("user_role")->hasForeignKey("FK_USER_ROLE_CREATED_BY")) {
            $this->addSql('ALTER TABLE "user_role" DROP CONSTRAINT FK_USER_ROLE_CREATED_BY');
        }

        if ($schema->getTable("user_role")->hasForeignKey("FK_USER_ROLE_MODIFIED_BY")) {
            $this->addSql('ALTER TABLE "user_role" DROP CONSTRAINT FK_USER_ROLE_MODIFIED_BY');
        }

        // Drop `role_audit` table foreign keys
        if ($schema->getTable("role_audit")->hasForeignKey("FK_ROLE_AUDIT_CREATED_BY")) {
            $this->addSql('ALTER TABLE "role_audit" DROP CONSTRAINT FK_ROLE_AUDIT_CREATED_BY');
        }

        if ($schema->getTable("role_audit")->hasForeignKey("FK_ROLE_AUDIT_ENTITY_ID")) {
            $this->addSql('ALTER TABLE "role_audit" DROP CONSTRAINT FK_ROLE_AUDIT_ENTITY_ID');
        }

        // Drop `role` table foreign keys
        if ($schema->getTable("role")->hasForeignKey("FK_ROLE_CREATED_BY")) {
            $this->addSql('ALTER TABLE "role" DROP CONSTRAINT FK_ROLE_CREATED_BY');
        }

        if ($schema->getTable("role")->hasForeignKey("FK_ROLE_MODIFIED_BY")) {
            $this->addSql('ALTER TABLE "role" DROP CONSTRAINT FK_ROLE_MODIFIED_BY');
        }

        // Drop `user_audit` table foreign keys
        if ($schema->getTable("user_audit")->hasForeignKey("FK_USER_AUDIT_CREATED_BY")) {
            $this->addSql('ALTER TABLE "user_audit" DROP CONSTRAINT FK_USER_AUDIT_CREATED_BY');
        }

        if ($schema->getTable("user_audit")->hasForeignKey("FK_USER_AUDIT_ENTITY_ID")) {
            $this->addSql('ALTER TABLE "user_audit" DROP CONSTRAINT FK_USER_AUDIT_ENTITY_ID');
        }

        // Drop `user` table foreign keys
        if ($schema->getTable("users")->hasForeignKey("FK_USER_CREATED_BY")) {
            $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_USER_CREATED_BY');
        }

        if ($schema->getTable("users")->hasForeignKey("FK_USER_MODIFIED_BY")) {
            $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_USER_MODIFIED_BY');
        }

        // Drop the tables
        if ($schema->hasTable("user_role_audit")) {
            $this->addSql('DROP TABLE "user_role"');
        }
        if ($schema->hasTable("user_role")) {
            $this->addSql('DROP TABLE "user_role"');
        }

        if ($schema->hasTable("role_audit")) {
            $this->addSql('DROP TABLE "role"');
        }
        if ($schema->hasTable("role")) {
            $this->addSql('DROP TABLE "role"');
        }

        if ($schema->hasTable("user_audit")) {
            $this->addSql('DROP TABLE "users"');
        }
        if ($schema->hasTable("users")) {
            $this->addSql('DROP TABLE "users"');
        }
    }
}
