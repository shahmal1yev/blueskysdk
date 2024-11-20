<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Contracts\HTTP\EndpointLexiconContract;
use Atproto\Contracts\HTTP\HTTPFactoryContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\HTTPFactory;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use GenericCollection\Exceptions\InvalidArgumentException;

class CreateSession implements EndpointLexiconContract
{
    use Endpoint;

    public function __construct(string $identifier, string $password, ?HTTPFactoryContract $factory = null)
    {
        $this->initialize($factory);

        $this->request = $this->request->method('POST')->parameters([
            'identifier' => $identifier,
            'password' => $password,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function response(ResponseContract $response): CreateSessionResponse
    {
        return new CreateSessionResponse($response);
    }
}
