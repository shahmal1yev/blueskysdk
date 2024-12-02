<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\HTTP\PSR\Factories\PSR17FactoryContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Factories\PSR\PSR17Factory;
use Atproto\Lexicons\Traits\MessageAlias;
use Atproto\Lexicons\Traits\MessageHandler;
use Atproto\Lexicons\Traits\MessageTrait;
use Atproto\Lexicons\Traits\RequestTrait;
use Psr\Http\Message\RequestInterface;

class Request implements RequestContract
{
    use MessageTrait;
    use RequestTrait;
    use MessageHandler;
    use MessageAlias;

    private PSR17FactoryContract $factory;
    protected RequestInterface $request;

    public function __construct(PSR17FactoryContract $factory = null)
    {
        $this->factory = $factory ?? PSR17Factory::createViaNyholm();
        $this->request = $this->factory->createRequest('GET', '');
    }
}
