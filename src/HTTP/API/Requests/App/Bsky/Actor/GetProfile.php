<?php

namespace Atproto\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Client;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\HTTP\API\APIRequest;
use Atproto\HTTP\Traits\Authentication;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;
use Exception;

class GetProfile extends APIRequest
{
    use Authentication;

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
        $missing = [];

        if (! $this->queryParameter('actor')) {
            $missing[] = 'actor';
        }

        if (! $this->header('Authorization')) {
            $missing[] = 'token';
        }

        if (! empty($missing)) {
            throw new MissingFieldProvidedException(implode(", ", $missing));
        }

        return $this;
    }

    public function resource(array $data): ResourceContract
    {
        return new GetProfileResource($data);
    }
}
