<?php

namespace Tests\Feature\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Enums\SearchPost\SortEnum;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Lexicons\App\Bsky\Embed\External;
use Atproto\Lexicons\App\Bsky\Feed\SearchPosts;
use Atproto\Lexicons\App\Bsky\RichText\Link;
use Atproto\Responses\Objects\FacetsObject;
use Atproto\Support\Arr;
use PHPUnit\Framework\TestCase;

class SearchPostsTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$client = new Client();
        self::$client->authenticate(
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD')
        );
    }

    /**
     * Helper to create a SearchPosts instance.
     */
    private function createSearchPosts(string $query): SearchPosts
    {
        return new SearchPosts(self::$client, $query);
    }

    /**
     * Helper to extract source from a post.
     */
    private function getPostSource($post)
    {
        try {
            return $post->embed();
        } catch (BadAssetCallException $e) {
            return $post->record()->facets();
        }
    }

    /**
     * Test searching posts with valid query and parameters.
     */
    public function testSearchPostsWithValidQuery(): void
    {
        $query = 'Hello World!';
        $response = $this->createSearchPosts($query)
            ->limit(5)
            ->sort(SortEnum::get('latest'))
            ->send();

        $this->assertNotEmpty($response->posts());
        $this->assertCount(5, $response->posts());
    }

    /**
     * Test searching posts with URL filter.
     */
    public function testSearchPostsWithUrl(): void
    {
        $query = 'example';
        $url = 'https://example.com';
        $response = $this->createSearchPosts($query)
            ->url($url)
            ->limit(3)
            ->send();

        $this->assertNotEmpty($response->posts());

        foreach ($response->posts() as $post) {
            $source = $this->getPostSource($post);

            if ($source instanceof External) {
                $this->assertStringContainsString($query, $source->uri());
            } elseif ($source instanceof FacetsObject) {
                $this->assertFacetsContainQuery($source, substr($query, strlen('https://')));
            }
        }
    }

    /**
     * Test searching posts by author.
     */
    public function testSearchPostsByAuthor(): void
    {
        $query = 'test';
        $author = 'shahmal1yevv.bsky.social';
        $response = $this->createSearchPosts($query)
            ->author($author)
            ->limit(3)
            ->send();

        $this->assertNotEmpty($response->posts());

        foreach ($response->posts() as $post) {
            $this->assertSame($author, $post->author()->handle());
        }
    }

    /**
     * Test searching posts with language filter.
     */
    public function testSearchPostsWithLang(): void
    {
        $query = 'Multilingual Content';
        $lang = 'de';
        $response = $this->createSearchPosts($query)
            ->lang($lang)
            ->limit(3)
            ->send();

        $this->assertNotEmpty($response->posts());

        foreach ($response->posts() as $post) {
            $this->assertTrue(in_array($lang, $post->record()->langs()));
        }
    }

    /**
     * Test searching posts with domain filter.
     */
    public function testSearchPostsWithDomain(): void
    {
        $query = 'test';
        $domain = 'shahmal1yev.dev';
        $response = $this->createSearchPosts($query)
            ->domain($domain)
            ->limit(3)
            ->send();

        $this->assertNotEmpty($response->posts());

        foreach ($response->posts() as $post) {
            $source = $this->getPostSource($post);

            if ($source instanceof External) {
                $this->assertStringContainsString($domain, $source->uri());
            } elseif (is_string($source)) {
                $this->assertStringContainsString($domain, $source);
            }
        }
    }

    /**
     * Test searching posts with a combination of filters.
     */
    public function testSearchPostsWithCombinedFilters(): void
    {
        $query = 'test';
        $author = 'shahmal1yevv.bsky.social';
        $lang = 'en';
        $url = 'https://shahmal1yev.dev';

        $response = $this->createSearchPosts($query)
            ->author($author)
            ->lang($lang)
            ->url($url)
            ->limit(1)
            ->send();

        $this->assertNotEmpty($response->posts());

        foreach ($response->posts() as $post) {
            $this->assertSame($author, $post->author()->handle());
            $this->assertTrue(in_array($lang, $post->record()->langs()));
            $this->assertStringContainsString('shahmal1yev', $post->record()->text());
        }
    }

    /**
     * Test searching posts with no results.
     */
    public function testSearchPostsNoResults(): void
    {
        $query = 'NonExistentContent12345';
        $response = $this->createSearchPosts($query)->send();
        $this->assertEmpty($response->posts());
    }

    /**
     * Test searching posts with pagination.
     */
    public function testSearchPostsWithPagination(): void
    {
        $query = 'Paginated Results';
        $request = $this->createSearchPosts($query);

        $firstResponse = $request->limit(3)->send();
        $cursor = $firstResponse->cursor();

        $this->assertNotEmpty($firstResponse->posts());
        $this->assertNotEmpty($cursor);

        $secondResponse = $request->cursor($cursor)->limit(3)->send();
        $this->assertNotEmpty($secondResponse->posts());

        $firstTexts = array_map(fn ($post) => $post->record()->text(), $firstResponse->posts()->toArray());
        $secondTexts = array_map(fn ($post) => $post->record()->text(), $secondResponse->posts()->toArray());

        $this->assertNotEquals($firstTexts, $secondTexts);
    }

    /**
     * Helper to assert facets contain a query.
     */
    private function assertFacetsContainQuery(FacetsObject $facets, string $url): void
    {
        foreach ($facets as $facet) {
            foreach ($facet->features() as $feature) {
                if ($feature instanceof Link) {
                    $feature = json_decode(json_encode($feature), true);
                    $this->assertStringContainsString($url, Arr::get($feature, 'uri'));
                }
            }
        }
    }
}
