<?php

namespace Atproto\Lexicons\App\Bsky\Graph;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Graph\GetFollowersResponse;

class GetFollowers extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->queryParameter('actor');
        }

        $this->queryParameter('actor', $actor);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function limit(int $limit = null)
    {
        if (is_null($limit)) {
            return (int) $this->queryParameter('limit') ?: null;
        }

        if (! ($limit >= 1 && $limit <= 100)) {
            throw new InvalidArgumentException("Limit must be between 1 and 100.");
        }

        $this->queryParameter('limit', $limit);

        return $this;
    }

    public function cursor(string $cursor = null)
    {
        if (is_null($cursor)) {
            return $this->queryParameter('cursor');
        }

        $this->queryParameter('cursor', $cursor);

        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new GetFollowersResponse($data);
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function build(): RequestContract
    {
        if (! $this->queryParameter('actor')) {
            throw new MissingFieldProvidedException('actor');
        }

        return $this;
    }
}
