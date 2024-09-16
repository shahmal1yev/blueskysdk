<?php

namespace Atproto\HTTP\API\Requests\Com\Atproto\Server;

use Atproto\Contracts\RequestContract;
use Atproto\HTTP\API\APIRequest;

class CreateSession extends APIRequest
{
    public function __construct(string $prefix, string $username, string $password)
    {
        parent::__construct($prefix);

        $this->method('POST')->parameters([
            'identifier' => $username,
            'password'   => $password,
        ]);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}