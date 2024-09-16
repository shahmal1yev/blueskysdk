<?php

namespace Atproto\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\HTTP\API\APIRequest;
use Exception;

class GetProfile extends APIRequest
{
    protected ?string $actor = null;
    protected ?string $token = null;

    public function __construct()
    {
        parent::__construct();
        $this->path('/app.bsky.actor.getProfile');
    }

    /**
     * @return RequestContract|string
     */
    public function actor(string $actor = null)
    {
        if (is_null($actor)) {
            return $this->actor;
        }

        $this->actor = $actor;

        $this->queryParameter('actor', $this->actor);

        return $this;
    }

    /**
     * @return RequestContract|string
     */
    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->token;
        }

        $this->token = $token;

        $this->header('Authorization', "Bearer $this->token");

        return $this;
    }

    /**
     * @throws Exception
     */
    public function build(): RequestContract
    {
        $fields = ['actor', 'token'];
        $missing = array_filter($fields, function ($field) {
            if (is_null($this->$field)) {
                return true;
            }
        });

        if (! empty($missing)) {
            throw new MissingFieldProvidedException(implode(", ", $missing));
        }

        return $this;
    }
}