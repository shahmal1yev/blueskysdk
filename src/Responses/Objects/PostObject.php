<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class PostObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    protected function casts(): array
    {
        return [
            'author' => UserObject::class,
            'indexedAt' => DatetimeObject::class,
            'viewer' => ViewerObject::class,
            'labels' => LabelsObject::class,
            'threadgate' => ThreadGateObject::class,
        ];
    }
}
