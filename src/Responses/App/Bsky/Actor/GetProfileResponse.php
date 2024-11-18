<?php

namespace Atproto\Responses\App\Bsky\Actor;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\UserObject;
use Atproto\Responses\Objects\ViewerObject;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @method string did()
 * @method string handle()
 * @method string displayName()
 * @method string description()
 * @method string avatar()
 * @method string banner()
 * @method int followersCount()
 * @method int followsCount()
 * @method int postsCount()
 * @method AssociatedObject associated()
 * @method JoinedViaStarterPackObject joinedViaStarterPack()
 * @method DatetimeObject indexedAt()
 * @method DatetimeObject createdAt()
 * @method ViewerObject viewer()
 * @method LabelsObject labels()
 */
class GetProfileResponse implements ResponseContract
{
    use UserObject;

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
