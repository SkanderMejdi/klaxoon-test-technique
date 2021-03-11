<?php

namespace App\Domain\Bookmark;

use Embed\Extractor;

final class Bookmark 
{
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
}