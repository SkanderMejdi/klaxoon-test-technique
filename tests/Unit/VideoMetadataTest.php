<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Metadata\VideoMetadata;
use Embed\Embed;
use PHPUnit\Framework\TestCase;

final class VideoMetadataTest extends TestCase
{
    private const URL = 'https://vimeo.com/288391939';
    private const WIDHT = 360;
    private const HEIGHT = 360;
    private const DURATION = 71;

    public function testCanBeCreatedFromEmbedExtractor(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $videoMetadata = VideoMetadata::fromEmbedExtractor($embedExtractor);

        $this->assertInstanceOf(VideoMetadata::class, $videoMetadata);
        $this->assertEquals(self::WIDHT, $videoMetadata->getWidht());
        $this->assertEquals(self::HEIGHT, $videoMetadata->getHeight());
        $this->assertEquals(self::DURATION, $videoMetadata->getDuration());
    }


    public function testCanBeCreatedArray(): void
    {
        $array = [
            'height' => self::HEIGHT,
            'width' => self::WIDHT,
            'duration' => self::DURATION,
        ];

        $videoMetadata = VideoMetadata::fromArray($array);

        $this->assertInstanceOf(VideoMetadata::class, $videoMetadata);
        $this->assertEquals(self::WIDHT, $videoMetadata->getWidht());
        $this->assertEquals(self::HEIGHT, $videoMetadata->getHeight());
        $this->assertEquals(self::DURATION, $videoMetadata->getDuration());
    }

    public function testCanBeSerialized(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $videoMetadata = VideoMetadata::fromEmbedExtractor($embedExtractor);
        $serializedVideoMetadata = $videoMetadata->serialize();

        $this->assertIsArray($serializedVideoMetadata);
        $this->assertSame([
            'height' => self::HEIGHT,
            'width' => self::WIDHT,
            'duration' => self::DURATION,
        ], $serializedVideoMetadata);
    }
}
