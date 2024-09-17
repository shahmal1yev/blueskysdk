<?php

namespace Atproto;

use Atproto\Traits\Authentication;
use Atproto\Traits\Smith;

class Client
{
    use Smith;
    use Authentication;
    protected static string $prefix = "Atproto\\HTTP\\API\\Requests\\";

    public function prefix(): string
    {
        return self::$prefix;
    }
}
