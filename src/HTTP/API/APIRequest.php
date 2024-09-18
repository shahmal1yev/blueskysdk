<?php

namespace Atproto\HTTP\API;

use Atproto\Contracts\HTTP\APIRequestContract;
use Atproto\HTTP\Request;

abstract class APIRequest extends Request implements APIRequestContract
{
    public function __construct()
    {
        $this->origin('https://public.api.bsky.app/xrpc/')->headers([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }
}
