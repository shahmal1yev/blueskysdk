<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Client;
use Atproto\Lexicons\APIRequest;
use SplSubject;

trait AuthenticatedEndpoint
{
    use Endpoint;

    public function __construct(Client $client)
    {
        if (! is_subclass_of(static::class, APIRequest::class)) {
            return;
        }

        parent::__construct($client);
        $this->update($client);
    }

    public function update(SplSubject $client): void
    {
        /** @var Client $client */
        parent::update($client);

        if ($authenticated = $client->authenticated()) {
            $this->header("Authorization", "Bearer " . $authenticated->accessJwt());
        }
    }

    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->header('Authorization');
        }

        $this->header('Authorization', "Bearer $token");

        return $this;
    }
}
