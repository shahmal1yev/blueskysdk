<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Carbon\Carbon;

class DatetimeObject implements ObjectContract
{
    use BaseObject;

    public function cast(): Carbon
    {
        return Carbon::parse($this->value);
    }
}
