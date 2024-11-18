<?php

namespace Atproto\Lexicons\App\Bsky\Actor;

use Atproto\Contracts\HTTP\EndpointLexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Actor\GetProfileResponse;
use GenericCollection\Exceptions\InvalidArgumentException;

class GetProfile implements EndpointLexiconContract
{
    use AuthenticatedEndpoint;

    /**
     * @param  string|null  $actor
     * @return RequestContract|string
     * @throws \Atproto\Exceptions\InvalidArgumentException
     */
    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->queryParameter('actor');
        }

        return $this->queryParameter('actor', $actor);
    }

    /**
     * @param  string|null  $token
     * @return RequestContract|string
     */
    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->header('Authorization');
        }

        $this->header('Authorization', "Bearer $token");

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function response(ResponseContract $response): GetProfileResponse
    {
        return new GetProfileResponse($response);
    }
}
