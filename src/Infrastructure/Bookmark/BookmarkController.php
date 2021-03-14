<?php

namespace App\Infrastructure\Bookmark;

use App\Domain\Bookmark\Bookmark;
use App\Domain\Bookmark\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    public function add(Request $request): Response
    {
        $this->bookmarkRepository->add(
            Bookmark::fromUrl($request->request->get('url'))
        );

        return $this->json(['success' => 'true']);
    }

    public function delete(int $id): Response
    {
        $this->bookmarkRepository->delete($id);

        return $this->json(['success' => 'true']);
    }

    public function edit(int $id, Request $request): Response
    {
        $this->bookmarkRepository->edit(
            $id,
            $request->request->get('key_words')
        );

        return $this->json(['success' => 'true']);
    }
}