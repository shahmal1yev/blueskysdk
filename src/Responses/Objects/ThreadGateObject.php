<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class ThreadGateObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    public function __construct($value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'lists' => ListsObject::class,
        ];
    }
}
