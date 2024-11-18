<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Contracts\Lexicons\RequestContract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

trait RequestTrait
{

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $_this = clone $this;

        $_this->request = $this->request->withRequestTarget($requestTarget);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestContract
    {
        $_this = clone $this;

        $_this->request = $this->request->withMethod($method);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestContract
    {
        $_this = clone $this;

        $_this->request = $this->request->withUri($uri, $preserveHost);

        return $_this;
    }
}
