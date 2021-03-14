<?php

namespace App\Domain\Bookmark;

interface BookmarkRepository
{
    /** @return BookMark[] */
    public function list(): array;

    public function add(Bookmark $bookmark): void;

    public function delete(int $id): void;

    public function edit(int $id, string $keyWords): void;
}