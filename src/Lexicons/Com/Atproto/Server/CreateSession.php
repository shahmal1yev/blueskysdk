<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;

class CreateSession extends APIRequest implements LexiconContract
{
    use Endpoint;

    public function __construct(Client $client, string $identifier, string $password)
    {
        parent::__construct($client);

        $this->method('POST')->parameters([
            'identifier' => $identifier,
            'password'   => $password,
        ]);
    }

    public function build(): RequestContract
    {
        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new CreateSessionResponse($data);
    }
}
