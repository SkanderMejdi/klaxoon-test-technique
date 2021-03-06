<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Metadata\ImageMetadata;
use PHPUnit\Framework\TestCase;

final class BookmarkTest extends TestCase
{
    private const ID = 1;
    private const URL = 'https://flic.kr/p/2d5pio1';
    private const WIDHT = 1024;
    private const HEIGHT = 682;
    private const TITLE = '2018 Klaxoon à Beaubourg';
    private const AUTHOR = 'Pierre Metivier';
    private const DATE_ADDED = '2018-10-11 00:00:00';
    private const TYPE = 'image';
    private const KEY_WORDS = 'klaxoon super top bien';

    public function testCanBeCreatedFromUrl(): void
    {
        $bookmark = Bookmark::fromUrl(self::URL);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertNull($bookmark->getId());
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertNull($bookmark->getDateAdded());
        $this->assertInstanceOf(ImageMetadata::class, $bookmark->getMetadata());
    }


    public function testCanBeCreatedArray(): void
    {
        $array = [
            'id' => self::ID,
            'url' => self::URL,
            'title' => self::TITLE,
            'author' => self::AUTHOR,
            'date_added' => self::DATE_ADDED,
            'width' => self::WIDHT,
            'height' => self::HEIGHT,
            'key_words' => self::KEY_WORDS,
        ];

        $bookmark = Bookmark::fromArray($array);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::ID, $bookmark->getId());
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertEquals(
            \DateTime::createFromFormat('Y-m-d H:i:s', self::DATE_ADDED),
            $bookmark->getDateAdded()
        );
        $this->assertInstanceOf(ImageMetadata::class, $bookmark->getMetadata());
        $this->assertEquals(self::KEY_WORDS, $bookmark->getKeyWords());
    }

    public function testCanBeSerialized(): void
    {
        $bookmark = Bookmark::fromUrl(self::URL);
        $serializedBookmark = $bookmark->serialize();

        $this->assertIsArray($serializedBookmark);
        $this->assertSame([
            'id' => null,
            'url' => self::URL,
            'title' => self::TITLE,
            'author' => self::AUTHOR,
            'date_added' => null,
            'metadata' => [
                'type' => self::TYPE,
                'height' => self::HEIGHT,
                'width' => self::WIDHT,
            ],
            'key_words' => null,
        ], $serializedBookmark);
    }
}
