<?php

namespace App\Domain\Metadata;

use Embed\Extractor;
use Webmozart\Assert\Assert;

final class ImageMetadata implements Metadata
{
    public const TYPE = 'image';
    public const PROVIDER = 'Flickr';
    private const MISSING_DATA = 'Missing metadata for the given image';

    private int $height;
    
    private int $width;

    private function __construct(int $height, int $width)
    {
        $this->height = $height;
        $this->width = $width;
    }

    static public function fromEmbedExtractor(Extractor $embed): self
    {
        Assert::notNull($embed->code, self::MISSING_DATA);
        Assert::integer($embed->code->height, self::MISSING_DATA);
        Assert::integer($embed->code->width, self::MISSING_DATA);

        return new ImageMetadata($embed->code->height, $embed->code->width);
    }

    static public function fromArray(array $array): self
    {
        Assert::keyExists($array, 'height', self::MISSING_DATA);
        Assert::keyExists($array, 'width', self::MISSING_DATA);

        return new ImageMetadata($array['height'], $array['width']);
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidht(): int
    {
        return $this->width;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function serialize(): array
    {
        return [
            'height' => $this->height,
            'width' => $this->width,
        ];
    }  
}