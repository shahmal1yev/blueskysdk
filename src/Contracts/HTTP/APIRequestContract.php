<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\RequestContract;

interface APIRequestContract extends RequestContract
{
    public function build(): RequestContract;
}
