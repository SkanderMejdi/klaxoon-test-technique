<?php

declare(strict_types=1);

namespace App\Tests\Functionnal\Infrastructure;

use App\Tests\Functionnal\Utils\BookmarkFaker;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpKernel\KernelInterface;

final class BookmarkContext implements Context
{
    private const LIST_PATH = '/bookmarks';
    private const VALID_VIDEO_URL = 'https://vimeo.com/503859030';
    private const BOOKMARK_ID = 1;

    private ?Response $response;

    private KernelInterface $kernel;

    private BookmarkFaker $bookmarkFaker;

    public function __construct(KernelInterface $kernel, BookmarkFaker $bookmarkFaker)
    {
        $this->kernel = $kernel;
        $this->bookmarkFaker = $bookmarkFaker;
    }

    /**
     * @BeforeScenario
     */
    public static function beforeScenario()
    {
        exec('php bin/console doctrine:database:drop --env=test --force');
        exec('php bin/console doctrine:database:create --env=test');
        exec('php bin/console doctrine:migrations:migrate --env=test --no-interaction');
    }

    /**
     * @Given :count bookmarks
     */
    public function givenSomeBookmarks(int $count): void
    {
        $this->bookmarkFaker->insertRandomBookmarks($count);
    }

    /**
     * @When I list all bookmarks
     */
    public function whenIListAllBookmarks(): void
    {
        $this->response = $this->kernel->handle(
            Request::create(self::LIST_PATH, 'GET')
        );
    }

    /**
     * @Then I have a list of :count bookmarks
     * @Then I have a list of :count bookmark
     */
    public function thenIHaveaListOAllBookmarks(int $count): void
    {
        $decodedResponse = \json_decode($this->response->getContent());
        Assert::notEmpty($decodedResponse);
        Assert::count($decodedResponse->bookmarks, $count);
    }

    /**
     * @When I add a bookmark
     */
    public function whenIAddABookmark(): void
    {
        $this->response = $this->kernel->handle(
            Request::create(self::LIST_PATH, 'PUT', [
                'url' => self::VALID_VIDEO_URL,
            ])
        );
    }

    /**
     * @When I delete this bookmark
     */
    public function whenIDeleteThisBookmark(): void
    {
        $this->response = $this->kernel->handle(
            Request::create(self::LIST_PATH, 'DELETE', [
                'id' => self::BOOKMARK_ID,
            ])
        );
    }
}
