<?php

namespace Atproto\Lexicons\Traits;

trait Endpoint
{
    use Serializable;

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
}
