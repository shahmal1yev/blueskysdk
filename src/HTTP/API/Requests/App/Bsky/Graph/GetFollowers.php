<?php

namespace Atproto\HTTP\API\Requests\App\Bsky\Graph;

use Atproto\Client;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Auth\AuthRequired;
use Atproto\HTTP\API\APIRequest;
use Atproto\Resources\App\Bsky\Graph\GetFollowersResource;

class GetFollowers extends APIRequest
{
    public function __construct(Client $client = null)
    {
        if (! $client) {
            return;
        }

        parent::__construct($client->prefix());

        if (! $client->authenticated()) {
            return;
        }

        try {
            $this->header('Authorization', 'Bearer ' . $client->authenticated()->accessJwt());
            $this->queryParameter('actor', $client->authenticated()->did());
        } catch (AuthRequired $e) {}
    }

    public function resource(array $data): ResourceContract
    {
        return new GetFollowersResource($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}