<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Server\GetSessionResponse;

class GetSession extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function response(array $data): ResponseContract
    {
        return new GetSessionResponse($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}
