<?php

namespace Atproto\HTTP\Traits;

use Atproto\Client;
use Atproto\HTTP\API\APIRequest;

trait Authentication
{
    public function __construct(Client $client)
    {
        if (! is_subclass_of(static::class, APIRequest::class)) {
            return;
        }

        parent::__construct($client);

        if ($authenticated = $client->authenticated()) {
            $this->header("Authorization", "Bearer " . $authenticated->accessJwt());
        }
    }
}