<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Contracts\HTTP\HTTPFactoryContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\HTTPFactory;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

trait Endpoint
{
    use Lexicon;

    private HTTPFactoryContract $factory;
    private RequestContract $request;

    public function __construct(HTTPFactoryContract $factory = null)
    {
        $this->factory = $factory ?? new HTTPFactory();
        $this->request = $this->factory->createRequest('GET', '');

        $this->initialize();
    }

    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url(),
            'path' => $this->path(),
            'method' => $this->method(),
            'headers' => $this->headers(),
            'parameters' => $this->parameters(),
            'queryParameters' => $this->queryParameters(),
        ];
    }

    public function send(): ResponseContract
    {
        $response = $this->request->send();

        return $this->response($this->factory->createFullCoverageResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        ));
    }

    abstract protected function response(ResponseContract $response): ResponseContract;

    private function initialize(): void
    {
        $this->header('Accept', 'application/json');
            $this->header('Content-Type', 'application/json');
            $this->url('https://bsky.social');

        $this->path(sprintf('/xrpc/%s', $this->nsid()));
        $this->method('GET')
        ;
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
        return $this->request = $this->request->withProtocolVersion($version);
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
        return $this->request = $this->request->withHeader($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this->request = $this->request->withAddedHeader($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        return $this->request = $this->request->withoutHeader($name);
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
        return $this->request = $this->request->withBody($body);
    }

    private function alias($name, ...$args)
    {
        if (($return = $this->request->$name(...$args)) instanceof RequestContract) {
            return $this->request = $return;
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function url($url = null)
    {
        return $this->alias(__FUNCTION__, $url);
    }

    /**
     * @inheritDoc
     */
    public function path(string $path = null)
    {
        return $this->alias(__FUNCTION__, $path);
    }

    /**
     * @inheritDoc
     */
    public function method(string $method = null)
    {
        return $this->alias(__FUNCTION__, $method);
    }

    /**
     * @inheritDoc
     */
    public function header(string $name, string $value = null)
    {
        return $this->alias(__FUNCTION__, $name, $value);
    }

    /**
     * @inheritDoc
     */
    public function parameter(string $name, $value = null)
    {
        return $this->alias(__FUNCTION__, $name, $value);
    }

    /**
     * @inheritDoc
     */
    public function queryParameter(string $name, $value = null)
    {
        return $this->alias(__FUNCTION__, $name, $value);
    }

    /**
     * @inheritDoc
     */
    public function headers($headers = null)
    {
        return $this->alias(__FUNCTION__, $headers);
    }

    /**
     * @inheritDoc
     */
    public function parameters($parameters = null)
    {
        return $this->alias(__FUNCTION__, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function queryParameters($queryParameters = null)
    {
        return $this->alias(__FUNCTION__, $queryParameters);
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
    public function withRequestTarget(string $requestTarget): RequestContract
    {
        return $this->request = $this->request->withRequestTarget($requestTarget);
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
        return $this->request = $this->request->withMethod($method);
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
        return $this->request = $this->request->withUri($uri, $preserveHost);
    }
}
