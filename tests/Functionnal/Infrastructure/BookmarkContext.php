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
    const LIST_PATH = '/bookmarks';

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
     * @Given some bookmarks
     */
    public function givenSomeBookmarks(): void
    {
        $this->bookmarkFaker->insertRandomBookmarks(5);
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
     * @Then I have a list of all bookmarks
     */
    public function thenIHaveaListOAllBookmarks(): void
    {
        $decodedResponse = \json_decode($this->response->getContent());
        Assert::notEmpty($decodedResponse);
        Assert::count($decodedResponse->bookmarks, 5);
    }
}
