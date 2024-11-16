<?php

namespace Atproto\Factories\PSR;

use Atproto\Contracts\HTTP\PSR\Factories\PSR17FactoryContract;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

trait PSR17FactoryBuilder
{
    public static function createViaNyholm(): PSR17FactoryContract
    {
        $factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        return new self(
            $factory,
            $factory,
            $factory,
            $factory,
            $factory,
            $factory,
        );
    }

    public static function create(
        RequestFactoryInterface $requestFactory,
        ResponseFactoryInterface $responseFactory,
        ServerRequestFactoryInterface $serverRequestFactory,
        StreamFactoryInterface $streamFactory,
        UploadedFileFactoryInterface $uploadedFileFactory,
        UriFactoryInterface $uriFactory
    ): PSR17FactoryContract
    {
        return new self(
            $requestFactory,
            $responseFactory,
            $serverRequestFactory,
            $streamFactory,
            $uploadedFileFactory,
            $uriFactory,
        );
    }
}