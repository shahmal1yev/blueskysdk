<?php

namespace Atproto\Traits;

use Atproto\Exceptions\BlueskyException;
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
        $request = $this->com()->atproto()->server()->createSession()->forge($identifier, $password);

        /** @var CreateSessionResponse $response */
        $response = $request->send();

        $this->authenticated = $response;

        $this->notify();
    }

    public function authenticated(): ?CreateSessionResponse
    {
        return $this->authenticated;
    }

    public function attach(SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
