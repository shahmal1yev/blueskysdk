<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class ReasonObject implements ObjectContract
{
    use BaseObject;
    use Castable;


    protected function casts(): array
    {
        return [
            'by' => AuthorObject::class,
            'indexedAt' => DateTimeObject::class,
        ];
    }
}