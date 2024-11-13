<?php

namespace Atproto\Traits;

use Atproto\Exceptions\BlueskyException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use SplObjectStorage;
use SplObserver;

trait Authentication
{
    private ?CreateSessionResponse $authenticated = null;
    private SplObjectStorage $observers;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    /**
     * @throws BlueskyException
     */
    public function authenticate(string $identifier, string $password): void
    {
        $this->authenticated = $this->com()->atproto()->server()->createSession()
            ->forge($identifier, $password)
            ->send();

        $this->notify();
    }

    public function authenticated(): ?CreateSessionResponse
    {
        return $this->authenticated;
    }

    public function attach($observer): void
    {
        if (! in_array(AuthenticatedEndpoint::class, class_uses_recursive($observer), true)) {
            throw new \RuntimeException(sprintf(
                "Authentication observer error: Class '%s' must use the '%s' trait",
                get_class($observer),
                AuthenticatedEndpoint::class
            ));
        }

        $this->observers->attach($observer);
    }

    public function detach($observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this->authenticated());
        }
    }
}
