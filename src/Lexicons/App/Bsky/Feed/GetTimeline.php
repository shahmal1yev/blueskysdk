<?php

namespace Atproto\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Feed\GetTimelineResponse;

class GetTimeline extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->update($client);

        $this->limit(50);
    }

    public function algorithm($algorithm = null)
    {
        if (is_null($algorithm)) {
            return $this->queryParameter('algorithm');
        }

        $this->queryParameter('algorithm', $algorithm);

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function limit(int $limit = null)
    {
        if (is_null($limit)) {
            return (int) $this->queryParameter('limit');
        }

        if ($limit < 1 || $limit > 100) {
            throw new InvalidArgumentException("The limit must be between or equal to 1 and 100.");
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
        return new GetTimelineResponse($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}
