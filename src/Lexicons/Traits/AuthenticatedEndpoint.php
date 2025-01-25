<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Client;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\Http\Response\ExpiredTokenException;
use Atproto\Exceptions\Http\Response\InvalidTokenException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Com\Atproto\Server\RefreshSession;
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

    public function send(): ResponseContract
    {
        try {
            return parent::send();
        } catch (BlueskyException $exception) {
            if ($exception instanceof ExpiredTokenException) {
                $this->client->authenticate(...array_merge(
                    $this->client->credentials(),
                    [$this->client->authenticated()]
                ));

                $this->update($this->client);

                return $this->send();
            }

            throw $exception;
        }
    }

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS)
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method($this->method);
    }
}
