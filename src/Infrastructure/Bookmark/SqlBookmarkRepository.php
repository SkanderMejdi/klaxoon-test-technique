<?php

namespace App\Infrastructure\Bookmark;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Bookmark\BookmarkRepository;
use Doctrine\DBAL\Connection;

class SqlBookmarkRepository implements BookmarkRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @return BookMark[] */
    public function list(): array
    {
        $sql = <<<SQL
            SELECT * FROM public.bookmark
            JOIN public.metadata ON bookmark.id = metadata.bookmark_id;
        SQL;

        $results = $this->connection->fetchAllAssociative($sql);

        return !empty($results)
        ? array_map(static function(array $result) {
            return Bookmark::fromArray($result);
        }, $results) : [];
    }
}