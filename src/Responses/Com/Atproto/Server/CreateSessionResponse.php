<?php

namespace Atproto\Responses\Com\Atproto\Server;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SplObjectStorage;
use SplObserver;
use SplSubject;

/**
 * @method string accessJwt()
 * @method string refreshJwt()
 * @method string handle()
 * @method string did()
 * @method string didDoc()
 * @method string email()
 * @method bool emailConfirmed()
 * @method bool emailAuthFactor()
 * @method bool active()
 * @method string|null status() The status of the account. Possible values are 'takendown', 'suspended', 'deactivated'. If `active` is `false`, this field may provide a reason for the account's inactivity.
 */
class CreateSessionResponse implements ResponseContract
{
    use BaseResponse;

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
}
