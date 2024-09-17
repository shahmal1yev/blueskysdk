<?php

namespace Atproto;

use Atproto\Traits\Authentication;
use Atproto\Traits\Smith;

class Client
{
    protected static string $prefix = "Atproto\\HTTP\\API\\Requests\\";

    use Smith;
    use Authentication;
}
