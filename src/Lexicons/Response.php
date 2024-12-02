<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\HTTP\PSR\Factories\PSR17FactoryContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Factories\PSR\PSR17Factory;
use Atproto\Lexicons\Traits\ResponseTrait;
use Atproto\Support\Arr;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseContract
{
    use ResponseTrait;

    private PSR17FactoryContract $factory;
    private ResponseInterface $response;

    public function __construct(?PSR17FactoryContract $factory = null)
    {
        $this->factory = ($factory ?? PSR17Factory::createViaNyholm());
        $this->response = $this->factory->createResponse();
    }
}
