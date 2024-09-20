<?php

namespace Atproto\Traits;

use Atproto\Exceptions\BlueskyException;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;

trait Authentication
{
    private ?CreateSessionResource $authenticated = null;

    /**
     * @throws BlueskyException
     */
    public function authenticate(string $identifier, string $password): void
    {
        $request = $this->com()->atproto()->server()->createSession()->forge($identifier, $password);

        /** @var CreateSessionResource $response */
        $response = $request->send();

        $this->authenticated = $response;
    }

    public function authenticated(): ?CreateSessionResource
    {
        return $this->authenticated;
    }
}
