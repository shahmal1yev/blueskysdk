<?php

namespace Atproto\HTTP;

use Atproto\Contracts\RequestContract;
use Atproto\HTTP\Traits\RequestBuilder;
use Atproto\HTTP\Traits\RequestHandler;

class Request implements RequestContract
{
    use RequestHandler;
    use RequestBuilder;
}
