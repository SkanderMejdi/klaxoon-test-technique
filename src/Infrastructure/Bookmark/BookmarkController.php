<?php

namespace App\Infrastructure\Bookmark;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Bookmark\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BookmarkController extends AbstractController
{
    private BookmarkRepository $bookmarkRepository;

    public function __construct(BookmarkRepository $bookmarkRepository)
    {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    public function list(): Response
    {
        $bookmarks = $this->bookmarkRepository->list();

        return $this->json([
            'bookmarks' => array_map(static function(Bookmark $bookmark) {
                return $bookmark->serialize();
            }, $bookmarks),
        ]);
    }
}