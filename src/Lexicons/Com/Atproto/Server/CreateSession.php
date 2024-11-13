<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use GenericCollection\Exceptions\InvalidArgumentException;

class CreateSession extends APIRequest implements LexiconContract
{
    use Endpoint;

    public function __construct(string $identifier, string $password)
    {
        parent::__construct();

        $this->request = $this->request->method('POST')->parameters([
            'identifier' => $identifier,
            'password'   => $password,
        ]);
    }

    public function build(): CreateSession
    {
        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function response(ResponseContract $data): CreateSessionResponse
    {
        return new CreateSessionResponse($data);
    }
}
