<?php

namespace Atproto;

use Atproto\Traits\Authentication;
use Atproto\Traits\Smith;
use SplSubject;

class Client implements SplSubject
{
    use Smith;
    use Authentication;
}
