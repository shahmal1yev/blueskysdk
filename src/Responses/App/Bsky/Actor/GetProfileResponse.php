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
        // TODO: Implement getProtocolVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        // TODO: Implement withProtocolVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        // TODO: Implement getHeaders() method.
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        // TODO: Implement hasHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        // TODO: Implement getHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        // TODO: Implement getHeaderLine() method.
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // TODO: Implement withAddedHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        // TODO: Implement withoutHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        // TODO: Implement getBody() method.
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        // TODO: Implement withBody() method.
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        // TODO: Implement getStatusCode() method.
    }

    /**
     * @inheritDoc
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        // TODO: Implement withStatus() method.
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        // TODO: Implement getReasonPhrase() method.
    }
}
