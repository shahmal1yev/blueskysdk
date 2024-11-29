<?php

namespace Atproto\Lexicons\App\Bsky\Graph;

use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Graph\GetFollowersResponse;

class GetFollowers implements AuthEndpointLexiconContract
{
    use AuthenticatedEndpoint;

    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->queryParameter('actor');
        }

        return $this->queryParameter('actor', $actor);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function limit(string $limit = null)
    {
        if (is_null($limit)) {
            return (int) $this->queryParameter('limit') ?: null;
        }

        if (! ($limit >= 1 && $limit <= 100)) {
            throw new InvalidArgumentException("Limit must be between 1 and 100.");
        }

        return $this->queryParameter('limit', $limit);
    }

    public function cursor(string $cursor = null)
    {
        if (is_null($cursor)) {
            return $this->queryParameter('cursor');
        }

        return $this->queryParameter('cursor', $cursor);
    }

    public function response(ResponseContract $data): ResponseContract
    {
        return new GetFollowersResponse($data);
    }
}
