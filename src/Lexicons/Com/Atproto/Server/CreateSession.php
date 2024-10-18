<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

class CreateSession extends APIRequest
{
    public function __construct(Client $client, string $identifier, string $password)
    {
        parent::__construct($client);

        $this->method('POST')->origin('https://bsky.social/')->parameters([
            'identifier' => $identifier,
            'password'   => $password,
        ]);
    }

    public function build(): RequestContract
    {
        return $this;
    }

    public function resource(array $data): ResourceContract
    {
        return new CreateSessionResource($data);
    }
}
