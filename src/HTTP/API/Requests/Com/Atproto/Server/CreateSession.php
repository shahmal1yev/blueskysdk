<?php

namespace Atproto\HTTP\API\Requests\Com\Atproto\Server;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\HTTP\API\APIRequest;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

class CreateSession extends APIRequest
{
    public function __construct(string $prefix, string $username, string $password)
    {
        parent::__construct($prefix);

        $this->method('POST')->origin('https://bsky.social/')->parameters([
            'identifier' => $username,
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
