<?php

namespace Atproto\Lexicons\App\Bsky\Actor;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Actor\GetProfileResponse;
use Exception;
use GenericCollection\Exceptions\InvalidArgumentException;

class GetProfile extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    /**
     * @return RequestContract|string
     */
    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->request->queryParameter('actor');
        }

        $this->request = $this->request->queryParameter('actor', $actor);

        return $this;
    }

    /**
     * @return RequestContract|string
     */
    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->request->header('Authorization');
        }

        $this->request = $this->request->header('Authorization', "Bearer $token");

        return $this;
    }

    /**
     * @throws Exception
     */
    public function build(): APIRequestContract
    {
        if (! $this->request->hasHeader('Authorization')) {
            throw new AuthMissingException();
        }

        if (! $this->request->queryParameter('actor')) {
            throw new MissingFieldProvidedException('actor');
        }

        return $this;
    }

    public function response(ResponseContract $data): GetProfileResponse
    {
        return new GetProfileResponse($data);
    }
}
