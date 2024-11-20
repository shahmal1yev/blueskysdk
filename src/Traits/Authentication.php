<?php

namespace Atproto\Traits;

use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
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

    public function attach(AuthEndpointLexiconContract $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(AuthEndpointLexiconContract $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        if (! $this->authenticated) {
            return;
        }

        foreach ($this->observers as $observer) {
            $this->authenticated->attach($observer);
        }

        $this->authenticated->notify();
    }
}
