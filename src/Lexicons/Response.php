<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\HTTP\PSR\Factories\PSR17FactoryContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\PSR\PSR17Factory;
use Atproto\Support\Arr;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseContract
{
    private PSR17FactoryContract $factory;
    private ResponseInterface $response;

    public function __construct(
        ?PSR17FactoryContract $factory = null
    )
    {
        $this->factory = ($factory ?? PSR17Factory::createViaNyholm());
        $this->response = $this->factory->createResponse();
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $_this = clone $this;

        $_this->response = $this->response->withProtocolVersion($version);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $_this = clone $this;
        $_this->response = $this->response->withHeader($name, $value);
        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $_this = clone $this;
        $_this->response = $this->response->withAddedHeader($name, $value);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $_this = clone $this;

        $_this->response = $this->response->withoutHeader($name);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $_this = clone $this;
        $_this->response = $this->response->withBody($body);

        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $_this = clone $this;
        $_this->response = $this->response->withStatus($code, $reasonPhrase);
        return $_this;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * @inheritDoc
     */
    public function get($name)
    {
        return Arr::get(json_decode($this->content(), true), $name);
    }

    private function content(): string
    {
        $content = $this->response->getBody()->getContents();

        $this->response->getBody()->rewind();

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function exist(string $name): bool
    {
        return Arr::exists(json_decode($this->content(), true), $name);
    }
}
