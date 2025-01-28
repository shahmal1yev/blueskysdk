<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Illuminate\Support\Collection;

class ArrayObject implements ObjectContract
{
    use BaseObject;

    public function cast(): Collection
    {
        return collect($this->value);
    }
}
