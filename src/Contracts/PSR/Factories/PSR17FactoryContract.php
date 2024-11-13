<?php

namespace Atproto\Contracts\PSR\Factories;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

interface PSR17FactoryContract extends RequestFactoryInterface, ResponseFactoryInterface, ServerRequestFactoryInterface, StreamFactoryInterface, UploadedFileFactoryInterface, UriFactoryInterface
{

}