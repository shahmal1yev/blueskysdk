<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

class CreateSession extends APIRequest implements LexiconContract
{
    use Endpoint;

    public function __construct(Client $client, string $identifier, string $password)
    {
        parent::__construct($client);

        $this->parameters([
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
