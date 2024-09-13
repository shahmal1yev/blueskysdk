<?php

namespace Atproto\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingProvidedFieldException;
use Atproto\HTTP\API\APIRequest;
use Exception;

class GetProfile extends APIRequest
{
    protected ?string $actor = null;

    public function __construct()
    {
        parent::__construct();
        $this->path('/xrpc/app.bsky.actor.getProfile');
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

        return $this;
    }

    /**
     * @throws Exception
     */
    public function build(): RequestContract
    {
        $field = 'actor';

        if (! $this->actor()) {
            throw new MissingProvidedFieldException($field);
        }

        $this->queryParameters([
            $field => $this->actor()
        ]);

        return $this;
    }
}