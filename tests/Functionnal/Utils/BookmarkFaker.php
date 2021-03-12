<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Utils;

use App\Domain\Bookmark\Bookmark;
use Doctrine\DBAL\Connection;
use Faker\Factory;
use Faker\Generator;

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
            'url' => $this->faker->url(),
            'title' => $this->faker->text(),
            'author' => $this->faker->name(),
            'date_added' => $this->faker->dateTime()->format(Bookmark::DATE_FORMAT),
        ]);
    }

    public function insertRandomBookmarks(int $count = 5): void
    {
        $insert = <<<SQL
            INSERT INTO public.bookmark (url, title, author, date_added)
            VALUES (:url, :title, :author, :dateAdded);
        SQL;

        for ($i = 0; $i < $count; $i++) {
            $bookmark = $this->aBookmark();

            $statment = $this->connection->prepare($insert);
            $statment->bindValue(':url', $bookmark->getUrl());
            $statment->bindValue(':title', $bookmark->getTitle());
            $statment->bindValue(':author', $bookmark->getAuthor());
            $statment->bindValue(':dateAdded', $bookmark->getDateAdded()->format(Bookmark::DATE_FORMAT));

            $statment->execute();
        }
    }
}
