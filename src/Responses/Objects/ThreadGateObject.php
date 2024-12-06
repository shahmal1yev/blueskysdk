<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class ThreadGateObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    protected function casts(): array
    {
        return [
            'lists' => ListsObject::class,
        ];
    }
}
