<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Bookmark\Bookmark;
use Embed\Embed;
use PHPUnit\Framework\TestCase;

final class BookmarkTest extends TestCase
{
    private const URL = 'https://www.youtube.com/embed/fqC4FdXLado';
    private const TITLE = 'YouTube';
    private const AUTHOR = null;
    private const DATE_ADDED = null;

    public function testCanBeCreatedFromEmbedExtractor(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $bookmark = Bookmark::fromEmbedExtractor($embedExtractor);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertEquals(self::DATE_ADDED, $bookmark->getDateAdded());
    }


    public function testCanBeCreatedArray(): void
    {
        $array = [
            'url' => self::URL,
            'title' => self::TITLE,
            'author' => self::AUTHOR,
            'date_added' => self::DATE_ADDED,
        ];

        $bookmark = Bookmark::fromArray($array);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::URL, $bookmark->getUrl());
        $this->assertEquals(self::TITLE, $bookmark->getTitle());
        $this->assertEquals(self::AUTHOR, $bookmark->getAuthor());
        $this->assertEquals(self::DATE_ADDED, $bookmark->getDateAdded());
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
            'date_added' => self::DATE_ADDED,
        ], $serializedBookmark);
    }
}
