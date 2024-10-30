<?php

namespace Atproto\Lexicons\App\Bsky\Actor;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;
use Exception;

class GetProfile extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    /**
     * @return RequestContract|string
     */
    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->queryParameter('actor');
        }

        $this->queryParameter('actor', $actor);

        return $this;
    }

    /**
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
     * @throws Exception
     */
    public function build(): RequestContract
    {
        if (! $this->header('Authorization')) {
            throw new AuthMissingException();
        }

        if (! $this->queryParameter('actor')) {
            throw new MissingFieldProvidedException('actor');
        }

        return $this;
    }

    public function resource(array $data): ResourceContract
    {
        return new GetProfileResource($data);
    }
}
