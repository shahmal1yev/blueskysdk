<?php

namespace Atproto\Lexicons\Traits;

trait Endpoint
{
    use Lexicon;

    protected string $method = 'GET';

    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url(),
            'origin' => $this->origin(),
            'path' => $this->path(),
            'method' => $this->method(),
            'headers' => $this->headers(),
            'parameters' => $this->parameters(),
            'queryParameters' => $this->queryParameters(),
        ];
    }

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS)
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method($this->method);
    }
}
