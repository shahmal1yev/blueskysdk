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
        $this->request = $this->factory->createRequest('GET', '');

        $this->initialize();

        $this->method('POST');
        $this->parameter('identifier', $identifier);
        $this->parameter('password', $password);
        $this->url('https://bsky.social');
        $this->path(sprintf("/xrpc/%s", $this->nsid()));
        $this->header('Content-Type', 'application/json');
        $this->header('Accept', 'application/json');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function response(ResponseInterface $response): CreateSessionResponse
    {
        return new CreateSessionResponse($response);
    }
}
