<?php

namespace App\Domain\Metadata;

use Embed\Extractor;
use Webmozart\Assert\Assert;

final class VideoMetadata implements Metadata
{
    public const TYPE = 'video';
    public const PROVIDER = 'Vimeo';
    private const MISSING_DATA = 'Missing metadata for the given video';

    private int $height;
    
    private int $width;
    
    private int $duration;

    private function __construct(int $height, int $width, int $duration)
    {
        $this->height = $height;
        $this->width = $width;
        $this->duration = $duration;
    }

    static public function fromEmbedExtractor(Extractor $embed): self
    {
        Assert::notNull($embed->code, self::MISSING_DATA);
        Assert::integer($embed->code->height, self::MISSING_DATA);
        Assert::integer($embed->code->width, self::MISSING_DATA);
        Assert::integer($embed->getOEmbed()->get('duration'), self::MISSING_DATA);

        return new VideoMetadata(
            $embed->code->height,
            $embed->code->width,
            $embed->getOEmbed()->get('duration')
        );
    }

    static public function fromArray(array $array): self
    {
        Assert::keyExists($array, 'height', self::MISSING_DATA);
        Assert::keyExists($array, 'width', self::MISSING_DATA);
        Assert::keyExists($array, 'duration', self::MISSING_DATA);

        return new VideoMetadata(
            $array['height'],
            $array['width'],
            $array['duration']
        );
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidht(): int
    {
        return $this->width;
    }

    public function getDuration(): int
    {
        return $this->duration;
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
            'duration' => $this->duration,
        ];
    }  
}