<?php

namespace Atproto\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Enums\SearchPost\SortEnum;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Feed\SearchPostsResponse;
use DateTimeImmutable;

class SearchPosts extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    private SortEnum $sort;
    private ?DateTimeImmutable $since = null;
    private ?DateTimeImmutable $until = null;
    private array $tag = [];

    public function __construct(Client $client, string $query)
    {
        parent::__construct($client);
        $this->update($client);

        $this->q($query)
            ->sort(SortEnum::get('latest'))
            ->limit(25);
    }

    public function q(string $query = null)
    {
        if (is_null($query)) {
            return $this->queryParameter('q') ?? null;
        }

        $this->queryParameter('q', $query);

        return $this;
    }

    public function sort(SortEnum $sort = null)
    {
        if (is_null($sort)) {
            return $this->sort->value ?? null;
        }

        $this->sort = $sort;
        $this->queryParameter('sort', $sort->value);

        return $this;
    }

    public function since(\DateTimeImmutable $since = null)
    {
        if (is_null($since)) {
            return $this->since;
        }

        $this->since = $since;
        $this->queryParameter('since', $this->since->format(DATE_ATOM));

        return $this;
    }

    public function until(DateTimeImmutable $until = null)
    {
        if (is_null($until)) {
            return $this->until;
        }

        $this->until = $until;
        $this->queryParameter('until', $this->until->format(DATE_ATOM));

        return $this;
    }

    public function mentions(string $mentions = null)
    {
        if (is_null($mentions)) {
            return $this->queryParameter('mentions') ?? null;
        }

        $this->queryParameter('mentions', $mentions);

        return $this;
    }

    public function author(string $author = null)
    {
        if (is_null($author)) {
            return $this->queryParameter('author') ?? null;
        }

        $this->queryParameter('author', $author);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function lang(string $lang = null)
    {
        if (is_null($lang)) {
            return $this->queryParameter('lang') ?? null;
        }

        if (! preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', $lang)) {
            throw new InvalidArgumentException('Invalid language code. Expected format: "xx" or "xx-XX".');
        }

        $this->queryParameter('lang', $lang);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function domain(string $domain = null)
    {
        if (is_null($domain)) {
            return $this->queryParameter('domain') ?? null;
        }

        if (! filter_var(sprintf("https://%s", $domain), FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid domain format.');
        }

        $this->queryParameter('domain', $domain);

        return $this;
    }

    public function url(string $url = null)
    {
        $backtrace = current(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1));

        if (strpos($backtrace['file'], "RequestHandler")) {
            return parent::url();
        }

        return $this->_url($url);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function _url(string $url = null)
    {
        if (is_null($url)) {
            return $this->queryParameter('url');
        }

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid URL format.');
        }

        $this->queryParameter('url', $url);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function tag(array $tags = null)
    {
        if (is_null($tags)) {
            return $this->tag;
        }

        $invalidTags = array_filter($tags, function ($tag) {
            if (! is_string($tag)) {
                return true;
            }

            if (mb_strlen($tag) > 640) {
                return true;
            }

            return false;
        });

        if (! empty($invalidTags)) {
            throw new InvalidArgumentException(
                'Tag must be a string and can\'t be longer than 640 characters: '
                . implode(', ', $invalidTags)
            );
        }

        $this->tag = $tags;
        $this->queryParameter('tag', $tags);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function limit(int $limit = null)
    {
        if (is_null($limit)) {
            return ((int) $this->queryParameter('limit')) ?: null;
        }

        if ($limit > 100 || $limit < 1) {
            throw new InvalidArgumentException('Limit must be between 1 and 100: 1 <= $limit <= 100');
        }

        $this->queryParameter('limit', $limit);

        return $this;
    }

    public function cursor(string $cursor = null)
    {
        if (is_null($cursor)) {
            return $this->queryParameter('cursor') ?? null;
        }

        $this->queryParameter('cursor', $cursor);

        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new SearchPostsResponse($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}
