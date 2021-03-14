<?php

namespace App\Domain\Bookmark;

use App\Domain\Metadata\ImageMetadata;
use App\Domain\Metadata\Metadata;
use App\Domain\Metadata\VideoMetadata;
use App\Domain\Serializable;
use Embed\Embed;

final class Bookmark implements Serializable
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';
    private const UNKNOWN_PROVIDER = "Unknown provider";

    private ?int $id;

    private string $url;

    private ?string $title;

    private ?string $author;
    
    private ?\DateTime $dateAdded;

    private Metadata $metadata;
    
    private ?string $keyWords;

    private function __construct(
        ?int $id,
        string $url,
        ?string $title,
        ?string $author,
        ?\DateTime $dateTime,
        Metadata $metadata,
        ?string $keyWords = null
    ) {
        $this->id = $id;
        $this->url = $url;
        $this->title = $title;
        $this->author = $author;
        $this->dateAdded = $dateTime;
        $this->metadata = $metadata;
        $this->keyWords = $keyWords;
    }

    static public function fromUrl(string $url): self
    {
        $embed = new Embed();
        $embedExtractor = $embed->get($url);

        switch ($embedExtractor->getOEmbed()->get('provider_name')) {
            case ImageMetadata::PROVIDER:
                $metadata = ImageMetadata::fromEmbedExtractor($embedExtractor);
                break;
            case VideoMetadata::PROVIDER:
                $metadata = VideoMetadata::fromEmbedExtractor($embedExtractor);
                break;
            default:
                throw new \Exception(self::UNKNOWN_PROVIDER);
        }

        return new Bookmark(
            null,
            (string) $embedExtractor->getRequest()->getUri(),
            $embedExtractor->title,
            $embedExtractor->authorName,
            $embedExtractor->publishedTime,
            $metadata
        );
    }

    static public function fromArray(array $array): self
    {
        $metadata = empty($array['duration'])
            ? ImageMetadata::fromArray($array)
            : VideoMetadata::fromArray($array);

        return new Bookmark(
            $array['id'],
            $array['url'],
            $array['title'],
            $array['author'],
            $array['date_added']
            ? \DateTime::createFromFormat(
                self::DATE_FORMAT, $array['date_added']
            ) : null,
            $metadata,
            $array['key_words']
        );
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKeyWords(): ?string
    {
        return $this->keyWords;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'title' => $this->title,
            'author' => $this->author,
            'date_added' => $this->dateAdded
                ? $this->dateAdded->format(\DateTimeInterface::ATOM)
                : null,
            'metadata' => array_merge(
                ['type' => $this->metadata->getType()],
                $this->metadata->serialize()
            ),
            'key_words' => $this->keyWords,
        ];
    }
}