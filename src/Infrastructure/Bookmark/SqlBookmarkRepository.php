<?php

namespace App\Infrastructure\Bookmark;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Bookmark\BookmarkRepository;
use App\Domain\Metadata\VideoMetadata;
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

    public function add(Bookmark $bookmark): void
    {
        $insertBookmark = <<<SQL
            INSERT INTO public.bookmark (url, title, author, date_added)
            VALUES (:url, :title, :author, :dateAdded);
        SQL;

        $insertMetadata = <<<SQL
            INSERT INTO public.metadata (bookmark_id, width, height, duration)
            VALUES (:bookmarkId, :width, :height, :duration);
        SQL;

        $statment = $this->connection->prepare($insertBookmark);
        $statment->bindValue(':url', $bookmark->getUrl());
        $statment->bindValue(':title', $bookmark->getTitle());
        $statment->bindValue(':author', $bookmark->getAuthor());
        $statment->bindValue(
            ':dateAdded',
            $bookmark->getDateAdded()
                ? $bookmark->getDateAdded()->format(Bookmark::DATE_FORMAT)
                : null
        );

        $statment->execute();

        $lastId = $this->connection->lastInsertId();

        $statment = $this->connection->prepare($insertMetadata);
        $statment->bindValue(':bookmarkId', $lastId);

        $metadata = $bookmark->getMetadata();
        if ($metadata->getType() === VideoMetadata::TYPE) {
            /** @var VideoMatadata $metadata */
            $statment->bindValue(':duration', $metadata->getDuration());
        } else {
            /** @var ImageMatadata $metadata */
            $statment->bindValue(':duration', null);
        }
        $statment->bindValue(':width', $metadata->getWidht());
        $statment->bindValue(':height', $metadata->getHeight());

        $statment->execute();
    }
}