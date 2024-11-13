<?php

namespace Atproto\Contracts;

use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\PSR\Factories\PSR17FactoryContract;
use Atproto\Contracts\Resources\ResponseContract;
use Psr\Http\Message\RequestInterface;

interface HTTPFactoryContract extends PSR17FactoryContract
{
    public function createRequest(string $method, $uri): RequestContract;
    public function createFullCoverageResponse(
        int $status = 200,
        array $headers = [],
        $body = null,
        string $version = '1.1',
        ?string $reason = null
    ): ResponseContract;
}
