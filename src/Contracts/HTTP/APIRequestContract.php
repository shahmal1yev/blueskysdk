<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\RequestContract;

interface APIRequestContract extends RequestContract
{
    const API_BASE_URL = 'https://public.api.bsky.app';
    const API_BASE_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function build(): RequestContract;
}
