<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class ReplyObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    protected function casts(): array
    {
        return [
            'grandparentAuthor' => AuthorObject::class,
            'root' => PostObject::class,
            'parent' => PostObject::class,
        ];
    }
}