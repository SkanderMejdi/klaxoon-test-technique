<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Domain\Metadata\ImageMetadata;
use Embed\Embed;
use PHPUnit\Framework\TestCase;

final class ImageMetadataTest extends TestCase
{
    private const URL = 'https://flic.kr/p/2d5pio1';
    private const WIDHT = 1024;
    private const HEIGHT = 682;

    public function testCanBeCreatedFromEmbedExtractor(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $imageMetadata = ImageMetadata::fromEmbedExtractor($embedExtractor);

        $this->assertInstanceOf(ImageMetadata::class, $imageMetadata);
        $this->assertEquals(self::WIDHT, $imageMetadata->getWidht());
        $this->assertEquals(self::HEIGHT, $imageMetadata->getHeight());
    }


    public function testCanBeCreatedArray(): void
    {
        $array = [
            'height' => self::HEIGHT,
            'width' => self::WIDHT,
        ];

        $imageMetadata = ImageMetadata::fromArray($array);

        $this->assertInstanceOf(ImageMetadata::class, $imageMetadata);
        $this->assertEquals(self::WIDHT, $imageMetadata->getWidht());
        $this->assertEquals(self::HEIGHT, $imageMetadata->getHeight());
    }

    public function testCanBeSerialized(): void
    {
        $embed = new Embed();
        $embedExtractor = $embed->get(self::URL);

        $imageMetadata = ImageMetadata::fromEmbedExtractor($embedExtractor);
        $serializedImageMetadata = $imageMetadata->serialize();

        $this->assertIsArray($serializedImageMetadata);
        $this->assertSame([
            'height' => self::HEIGHT,
            'width' => self::WIDHT,
        ], $serializedImageMetadata);
    }
}
