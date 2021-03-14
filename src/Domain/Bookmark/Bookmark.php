<?php

namespace App\Domain\Bookmark;

use App\Domain\Metadata\ImageMetadata;
use App\Domain\Metadata\Metadata;
use App\Domain\Metadata\VideoMetadata;
use App\Domain\Serializable;
use Embed\Extractor;

final class Bookmark implements Serializable
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    private const UNKNOWN_PROVIDER = "Unknown provider";

    private string $url;

    private ?string $title;

    private ?string $author;
    
    private ?\DateTime $dateAdded;

    private Metadata $metadata;

    private function __construct(
        string $url,
        ?string $title,
        ?string $author,
        ?\DateTime $dateTime,
        Metadata $metadata
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->author = $author;
        $this->dateAdded = $dateTime;
        $this->metadata = $metadata;
    }

    static public function fromEmbedExtractor(Extractor $embed): self
    {
        switch ($embed->getOEmbed()->get('provider_name')) {
            case ImageMetadata::PROVIDER:
                $metadata = ImageMetadata::fromEmbedExtractor($embed);
                break;
            case VideoMetadata::PROVIDER:
                $metadata = VideoMetadata::fromEmbedExtractor($embed);
                break;
            default:
                throw new \Exception(self::UNKNOWN_PROVIDER);
        }

        return new Bookmark(
            (string) $embed->getRequest()->getUri(),
            $embed->title,
            $embed->authorName,
            $embed->publishedTime,
            $metadata
        );
    }

    static public function fromArray(array $array): self
    {
        $metadata = empty($array['duration'])
            ? ImageMetadata::fromArray($array)
            : VideoMetadata::fromArray($array);

        return new Bookmark(
            $array['url'],
            $array['title'],
            $array['author'],
            $array['date_added']
            ? \DateTime::createFromFormat(
                self::DATE_FORMAT, $array['date_added']
            ) : null,
            $metadata
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getDateAdded(): ?\DateTime
    {
        return $this->dateAdded;
    }

    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    public function serialize(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'author' => $this->author,
            'date_added' => $this->dateAdded
                ? $this->dateAdded->format(\DateTimeInterface::ATOM)
                : null,
            'metadata' => array_merge(
                ['type' => $this->metadata->getType()],
                $this->metadata->serialize()
            )
        ];
    }
}