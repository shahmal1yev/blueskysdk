<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\App\Bsky\Actor\GetProfile;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;

class CreateSession extends APIRequest implements LexiconContract
{
    use Endpoint;

    private ?CreateSessionResponse $session = null;
    private string $identifier;
    private string $password;

    public function __construct(
        Client $client,
        string $identifier,
        string $password,
        CreateSessionResponse $session = null
    )
    {
        $this->client = $client;

        $this->identifier = $identifier;
        $this->password = $password;

        if ($session) {
            $this->session = $session;
        }

        $this->initialize();
    }

    public function build(): RequestContract
    {
        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new CreateSessionResponse($data);
    }

    private function withAccessToken(): self
    {
        $this->path(sprintf("/xrpc/%s", (new GetProfile($this->client))->nsid()))
            ->method('GET')
            ->headers(self::API_BASE_HEADERS + ['Authorization' => "Bearer " . $this->session->accessJwt()])
            ->queryParameters(['actor' => $this->identifier])
            ->parameters([]);

        return $this;
    }

    private function withCredentials(): self
    {
        $this->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method('POST')
            ->headers(self::API_BASE_HEADERS)
            ->parameters([
                'identifier' => $this->identifier,
                'password' => $this->password,
            ]);

        return $this;
    }

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL);

        if ($this->session) {
            $this->withAccessToken();
        } else {
            $this->withCredentials();
        }
    }

    public function send(): ResponseContract
    {
        try {
            $response = parent::send();

            return $this->session ?: $response;
        } catch (BlueskyException $e) {
            if ($this->session && in_array($e->getCode(), [400, 401, 403])) {
                $this->withCredentials();

                return parent::send();
            }

            throw $e;
        }
    }
}
