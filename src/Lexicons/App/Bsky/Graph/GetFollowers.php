<?php

namespace Atproto\Lexicons\App\Bsky\Graph;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Resources\App\Bsky\Graph\GetFollowersResource;

class GetFollowers extends APIRequest
{
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

    public function resource(array $data): ResourceContract
    {
        return new GetFollowersResource($data);
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
