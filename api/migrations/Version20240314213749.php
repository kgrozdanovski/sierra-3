<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240314213749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add public_id column to users table and create short ID generator function';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('users')
            && !$schema->getTable('users')->hasColumn('public_id')
        ) {
            $this->addSql('ALTER TABLE "users" ADD public_id VARCHAR(7) UNIQUE DEFAULT NULL');
        }

        $this->addSql("CREATE OR REPLACE FUNCTION fn_generate_short_id(table_name TEXT) RETURNS VARCHAR(7) AS $$
            DECLARE
                short_id VARCHAR(7);
                id_exists INT;
            BEGIN
                LOOP
                    short_id := LPAD(CAST(FLOOR(RANDOM() * 10000000) AS TEXT), 7, '0');
                    EXECUTE format('SELECT COUNT(*) FROM %I WHERE public_id = $1', table_name) INTO id_exists USING short_id;
            
                    IF id_exists = 0 THEN
                        EXIT;
                    END IF;
                END LOOP;
                RETURN short_id;
            END;
            $$ LANGUAGE plpgsql;");

        // Generate short IDs for existing users
        $this->addSql("UPDATE users SET public_id = fn_generate_short_id('users') WHERE public_id IS NULL");

        // Make the public_id column NOT NULL
        $this->addSql('ALTER TABLE "users" ALTER public_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('users')
            && $schema->getTable('users')->hasColumn('public_id')
        ) {
            $this->addSql('ALTER TABLE "users" DROP public_id');
        }

        $this->addSql('DROP FUNCTION IF EXISTS fn_generate_short_id');
    }
}
