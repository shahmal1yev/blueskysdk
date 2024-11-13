<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;

trait AuthenticatedEndpoint
{
    use Endpoint;

    /**
     * @param  CreateSessionResponse  $session
     */
    public function update(CreateSessionResponse $session): void
    {
        $this->request = $this->request->header("Authorization", "Bearer " . $session->accessJwt());
    }

    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->request->header('Authorization');
        }

        $this->request = $this->request->header('Authorization', "Bearer $token");

        return $this;
    }

    protected function initialize(): void
    {
        $this->request = $this->request->url(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS)
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method($this->method);

        return;
    }
}
