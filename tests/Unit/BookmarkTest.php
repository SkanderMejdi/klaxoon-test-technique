<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Metadata\ImageMetadata;
use Embed\Embed;
use PHPUnit\Framework\TestCase;

final class BookmarkTest extends TestCase
{
    private const URL = 'https://flic.kr/p/2d5pio1';
    private const WIDHT = 1024;
    private const HEIGHT = 682;
    private const TITLE = '2018 Klaxoon à Beaubourg';
    private const AUTHOR = 'Pierre Metivier';
    private const DATE_ADDED = '2018-10-11 00:00:00';
    private const TYPE = 'image';

    public function testCanBeCreatedFromEmbedExtractor(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $bookmark = Bookmark::fromEmbedExtractor($embedExtractor);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertNull($bookmark->getDateAdded());
        $this->assertInstanceOf(ImageMetadata::class, $bookmark->getMetadata());
    }


    public function testCanBeCreatedArray(): void
    {
        $array = [
            'url' => self::URL,
            'title' => self::TITLE,
            'author' => self::AUTHOR,
            'date_added' => self::DATE_ADDED,
            'width' => self::WIDHT,
            'height' => self::HEIGHT,
        ];

        $bookmark = Bookmark::fromArray($array);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertEquals(
            \DateTime::createFromFormat('Y-m-d H:i:s', self::DATE_ADDED),
            $bookmark->getDateAdded()
        );
        $this->assertInstanceOf(ImageMetadata::class, $bookmark->getMetadata());
    }

    public function testCanBeSerialized(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $bookmark = Bookmark::fromEmbedExtractor($embedExtractor);
        $serializedBookmark = $bookmark->serialize();

        $this->assertIsArray($serializedBookmark);
        $this->assertSame([
            'url' => self::URL,
            'title' => self::TITLE,
            'author' => self::AUTHOR,
            'date_added' => null,
            'metadata' => [
                'type' => self::TYPE,
                'height' => self::HEIGHT,
                'width' => self::WIDHT,
            ]
        ], $serializedBookmark);
    }
}
