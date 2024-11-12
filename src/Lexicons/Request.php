<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Lexicons\Traits\RequestBuilder;
use Atproto\Lexicons\Traits\RequestHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestContract
{
    use RequestHandler;
    use RequestBuilder;

    private Psr17Factory $factory;
    private RequestInterface $request;

    public function __construct(RequestInterface $request = null)
    {
        $this->factory = new Psr17Factory();
        $this->request = $request ?: $this->factory->createRequest('GET', '');
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($this->getProtocolVersion() === $version) {
            return $this;
        }

        $_this = clone $this;

        $_this->request = $_this->request->withProtocolVersion($version);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        return $this->request->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $_this = clone $this;

        $_this->request = $this->request->withHeader($name, $value);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $_this = clone $this;

        $_this->request = $_this->request->withAddedHeader($name, $value);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $_this = clone $this;

        $_this->request = $_this->request->withoutHeader($name);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $_this = clone $this;

        $_this->request = $_this->request->withBody($body);

        return $_this;
    }

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
    public function withMethod(string $method): RequestInterface
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
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $_this = clone $this;

        $_this->request = $this->request->withUri($uri, $preserveHost);

        return $_this;
    }
}
