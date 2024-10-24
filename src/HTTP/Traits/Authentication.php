<?php

namespace Atproto\HTTP\Traits;

use Atproto\Client;
use Atproto\HTTP\API\APIRequest;
use SplSubject;

trait Authentication
{
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
}
