<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Lexicons\Traits\RequestBuilder;
use Atproto\Lexicons\Traits\RequestHandler;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Request implements RequestContract
{
    use RequestHandler;
    use RequestBuilder;

    private string $protocol = '1.1';
    private StreamInterface $body;

    public function getProtocolVersion(): string
    {
        return $this->protocol;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        if ($version === $this->protocol) {
            return $this;
        }

        $instance = clone $this;
        $instance->protocol = $version;

        return $instance;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader(string $name): array
    {
        return $this->hasHeader($name) ? $this->headers[strtolower($name)] : [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        if (! is_array($value) && ! is_string($value)) {
            throw new \InvalidArgumentException('$value must be an array or string');
        }

        $instance = clone $this;
        $instance->headers[strtolower($name)] = $value;

        return $instance;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        if (! is_array($value) && ! is_string($value)) {
            throw new \InvalidArgumentException('$value must be an array or string');
        }

        if (is_string($value)) {
            $value = [$value];
        }

        return $this->withHeader(
            $name,
            array_reduce(
                [$this->getHeader($name), $value],
                fn ($carry, array $next) => [...($carry ?: []), ...$next]
            )
        );
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $headers = $this->getHeaders();

        unset($headers[strtolower($name)]);

        $instance = clone $this;
        $instance->headers = $headers;

        return $instance;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $instance = clone $this;
        $instance->body = $body;

        return $instance;
    }
}
