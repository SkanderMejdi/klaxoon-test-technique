<?php

namespace App\Domain\Bookmark;

interface BookmarkRepository
{
    /** @return BookMark[] */
    public function list(): array;
}