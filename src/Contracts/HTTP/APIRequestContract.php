<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\RequestContract;

interface APIRequestContract extends RequestContract
{
    public const API_BASE_URL = 'https://bsky.social';
    public const API_BASE_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function build(): RequestContract;
}
