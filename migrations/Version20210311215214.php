<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210311215214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Bookmark table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE public.bookmark (
                id serial PRIMARY KEY NOT NULL,
                url VARCHAR(255) NOT NULL,
                title VARCHAR(255) DEFAULT NULL,
                author VARCHAR(255) DEFAULT NULL,
                date_added TIMESTAMP DEFAULT NULL
            )'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP public.bookmark');
    }
}
