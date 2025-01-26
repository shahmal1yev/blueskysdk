<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse as SessionResponse;
use SplSubject;

/**
 * @method CreateSessionResponse send()
 */
class RefreshSession extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function __construct(Client $client, string $token = null)
    {
        parent::__construct($client);
        $this->update($client);

        $this->method('POST');

        if ($token) {
            $this->headers(array_merge(self::API_BASE_HEADERS, [
                'Authorization' => "Bearer {$token}"
            ]));
        }
    }

    public function update(SplSubject $client): void
    {
        parent::update($client);

        if ($authenticated = $client->authenticated()) {
            $this->header("Authorization", "Bearer " . $authenticated->refreshJwt());
        }
    }

    public function token(string $token = null)
    {
        if (! $token) {
            return str_replace("Bearer ", '', $this->header('Authorization'));
        }

        $this->header("Authorization", "Bearer $token");

        return $this;
    }

    public function build(): RequestContract
    {
        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new SessionResponse($data);
    }
}
