<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314205300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add Metadata table for video and image infos';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE public.metadata (
                id serial PRIMARY KEY NOT NULL,
                bookmark_id INT NOT NULL,
                width INT NOT NULL,
                height INT NOT NULL,
                duration INT DEFAULT NULL,
                CONSTRAINT fk_bookmark
                    FOREIGN KEY(bookmark_id) 
                        REFERENCES bookmark(id) ON DELETE CASCADE
            )'
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP public.metadata');
    }
}
