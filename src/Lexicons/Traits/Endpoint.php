<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Contracts\HTTP\HTTPFactoryContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\HTTPFactory;

trait Endpoint
{
    use Lexicon;
    use MessageTrait;
    use RequestTrait;
    use MessageAlias;

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
        $this->request = $this->request->url('https://bsky.social')
            ->path(sprintf('/xrpc/%s', $this->nsid()))
            ->method('GET')
            ->headers([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]);
    }
}
