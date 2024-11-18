<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Contracts\HTTP\EndpointLexiconContract;
use Atproto\Contracts\HTTP\HTTPFactoryContract;
use Atproto\Factories\HTTPFactory;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use GenericCollection\Exceptions\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class CreateSession implements EndpointLexiconContract
{
    use Endpoint;

    public function __construct(?HTTPFactoryContract $factory, string $identifier, string $password)
    {
        $this->factory = $factory ?? new HTTPFactory();
        $this->request = $this->factory->createRequest('POST', '');

        $this->initialize();

        $this->method('POST')->parameters([
            'identifier' => $identifier,
            'password' => $password,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function response(ResponseInterface $response): CreateSessionResponse
    {
        return new CreateSessionResponse($response);
    }
}
