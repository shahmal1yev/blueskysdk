<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\RequestContract;
use SplObserver;

interface APIRequestContract extends RequestContract, SplObserver
{
    public const API_BASE_URL = 'https://bsky.social';
    public const API_BASE_HEADERS = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function build(): RequestContract;
}
