<?php

namespace Atproto\Traits;

use Atproto\Exceptions\BlueskyException;
use Atproto\HTTP\API\Requests\Com\Atproto\Server\CreateSession;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

trait Authentication
{
    private ?CreateSessionResource $authenticated = null;

    /**
     * @throws BlueskyException
     */
    public function authenticate(string $identifier, string $password): void
    {
        $request = new CreateSession(self::$prefix, $identifier, $password);

        /** @var CreateSessionResource $response */
        $response = $request->send();

        $this->authenticated = $response;
    }

    public function authenticated(): ?CreateSessionResource
    {
        return $this->authenticated;
    }
}
