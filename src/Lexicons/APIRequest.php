<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\HTTPFactoryContract;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\HTTPFactory;
use Psr\Http\Message\ResponseInterface;

abstract class APIRequest implements APIRequestContract
{
    private HTTPFactoryContract $factory;
    protected RequestContract $request;

    public function __construct(HTTPFactoryContract $factory = null)
    {
        $this->factory = ($factory ?: new HTTPFactory());
        $this->request = $this->factory->createRequest('GET', '');

        $this->initialize();
    }

    public function send(): ResponseContract
    {
        return $this->response($this->factory->createFullCoverageResponse(...array_values($this->request->send())));
    }

    abstract protected function initialize(): void;

    abstract public function response(ResponseContract $data): ResponseContract;
}
