<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Bookmark\Bookmark;
use Embed\Embed;
use PHPUnit\Framework\TestCase;

final class BookmarkTest extends TestCase
{
    private const URL = 'https://www.youtube.com/embed/fqC4FdXLado';

    public function testCanBeCreatedFromEmbedExtractor(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $bookmark = Bookmark::fromEmbedExtractor($embedExtractor);

        $this->assertInstanceOf(Bookmark::class, $bookmark);
        $this->assertEquals(self::URL, $bookmark->getUrl());
    }
}
