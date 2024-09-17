<?php

namespace Atproto\Traits;

use Atproto\Exceptions\BlueskyException as BlueskyExceptionAlias;
use Atproto\Exceptions\Http\Response\AuthenticationRequiredException;
use Atproto\HTTP\API\Requests\Com\Atproto\Server\CreateSession;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

trait Authentication
{
    private ?CreateSessionResource $authenticated = null;

    /**
     * @throws BlueskyExceptionAlias
     */
    public function authenticate(string $identifier, string $password): void
    {
        $request = new CreateSession(self::$prefix, $identifier, $password);

        /** @var CreateSessionResource $response */
        $response = $request->send();

        $this->authenticated = $response;
    }

    /**
     * @throws AuthenticationRequiredException
     */
    public function authenticated(): ?CreateSessionResource
    {
        return $this->authenticated;
    }
}
