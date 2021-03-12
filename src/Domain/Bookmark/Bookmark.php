<?php

namespace App\Domain\Bookmark;

use Embed\Extractor;

final class Bookmark 
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    private string $url;

    private ?string $title;

    private ?string $author;
    
    private ?\DateTime $dateAdded;

    private function __construct(
        string $url,
        ?string $title,
        ?string $author,
        ?\DateTime $dateTime
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->author = $author;
        $this->dateAdded = $dateTime;
    }

    static public function fromEmbedExtractor(Extractor $embed): self
    {
        return new Bookmark(
            (string) $embed->url,
            $embed->title,
            $embed->authorName,
            $embed->publishedTime
        );
    }

    static public function fromArray(array $array): self
    {
        return new Bookmark(
            $array['url'],
            $array['title'],
            $array['author'],
            $array['date_added']
            ? \DateTime::createFromFormat(
                self::DATE_FORMAT, $array['date_added']
            ) : null
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

    public function serialize(): array
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
            'author' => $this->author,
            'date_added' => $this->dateAdded
                ? $this->dateAdded->format(\DateTimeInterface::ATOM)
                : null,
        ];
    }
}