<?php

namespace Atproto\Factories\PSR;

use Atproto\Contracts\PSR\Factories\PSR17FactoryContract;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use const UPLOAD_ERR_OK;

class PSR17Factory implements PSR17FactoryContract
{
    use PSR17FactoryBuilder;

    private RequestFactoryInterface $requestFactory;
    private ResponseFactoryInterface $responseFactory;
    private ServerRequestFactoryInterface $serverRequestFactory;
    private StreamFactoryInterface $streamFactory;
    private UriFactoryInterface $uriFactory;
    private UploadedFileFactoryInterface $uploadedFileFactory;

    protected function __construct(
        RequestFactoryInterface $requestFactory,
        ResponseFactoryInterface $responseFactory,
        ServerRequestFactoryInterface $serverRequestFactory,
        StreamFactoryInterface $streamFactory,
        UploadedFileFactoryInterface $uploadedFileFactory,
        UriFactoryInterface $uriFactory
    ) {
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
        $this->serverRequestFactory = $serverRequestFactory;
        $this->streamFactory = $streamFactory;
        $this->uploadedFileFactory = $uploadedFileFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        return $this->requestFactory->createRequest($method, $uri);
    }

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return $this->responseFactory->createResponse($code, $reasonPhrase);
    }

    /**
     * @inheritDoc
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return $this->serverRequestFactory->createRequest($method, $uri);
    }

    /**
     * @inheritDoc
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return $this->streamFactory->createStream($content);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return $this->streamFactory->createStreamFromFile($filename, $mode);
    }

    /**
     * @inheritDoc
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return $this->streamFactory->createStreamFromResource($resource);
    }

    /**
     * @inheritDoc
     */
    public function createUploadedFile(
        StreamInterface $stream,
        ?int $size = null,
        int $error = UPLOAD_ERR_OK,
        ?string $clientFilename = null,
        ?string $clientMediaType = null
    ): UploadedFileInterface {
        return $this->uploadedFileFactory->createUploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }

    /**
     * @inheritDoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }
}
