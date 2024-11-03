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

        $this->method = 'POST';

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

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS)
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method($this->method);
    }
}
