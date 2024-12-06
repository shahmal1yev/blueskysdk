<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Closure;
use GenericCollection\GenericCollection;

class ListsObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    public function item($data): ObjectContract
    {
        return new ListObject($data);
    }

    protected function type(): Closure
    {
        return fn ($value): bool => $value instanceof ListObject;
    }
}
