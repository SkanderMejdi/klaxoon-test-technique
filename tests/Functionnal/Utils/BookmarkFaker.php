<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Utils;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Metadata\VideoMetadata;
use Doctrine\DBAL\Connection;
use Faker\Factory;
use Faker\Generator;

use function PHPUnit\Framework\isType;

final class BookmarkFaker
{
    private Generator $faker;

    private Connection $connection;
    
    public function __construct(Connection $connection, Generator $faker)
    {
        $this->connection = $connection;
        $this->faker = $faker;
    }

    public function aBookmark(): Bookmark
    {
        $faker = Factory::create();

        return Bookmark::fromArray([
            'id' => $this->faker->randomNumber(),
            'url' => $this->faker->url(),
            'title' => $this->faker->text(),
            'author' => $this->faker->name(),
            'date_added' => $this->faker->boolean()
                ? $this->faker->dateTime()->format(Bookmark::DATE_FORMAT)
                : null,
            'width' => $this->faker->randomNumber(),
            'height' => $this->faker->randomNumber(),
            'duration' => $this->faker->boolean() ? $this->faker->randomNumber() : null,
            'key_words' => $this->faker->boolean() ? $this->faker->text() : null,
        ]);
    }

    public function insertRandomBookmarks(int $count = 5): void
    {
        $insertBookmark = <<<SQL
            INSERT INTO public.bookmark (url, title, author, date_added, key_words)
            VALUES (:url, :title, :author, :dateAdded, :keyWords);
        SQL;

        $insertMetadata = <<<SQL
            INSERT INTO public.metadata (bookmark_id, width, height, duration)
            VALUES (:bookmarkId, :width, :height, :duration);
        SQL;

        for ($i = 0; $i < $count; $i++) {
            $bookmark = $this->aBookmark();

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
            $statment->bindValue(':keyWords', $bookmark->getKeyWords());

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
}
