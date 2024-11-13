<?php

namespace Atproto\Contracts\Lexicons;

use Psr\Http\Message\ResponseInterface;

interface APIRequestContract
{
    public const API_BASE_URL = 'https://bsky.social';
    public const API_BASE_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function build(): APIRequestContract;
    public function send(): ResponseInterface;
}
