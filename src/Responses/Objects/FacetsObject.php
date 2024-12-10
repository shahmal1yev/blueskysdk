<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Closure;
use GenericCollection\GenericCollection;

class FacetsObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    protected function item($data): ObjectContract
    {
        return new FacetObject($data);
    }

    protected function type(): Closure
    {
        return fn ($value): bool => $value instanceof FacetObject;
    }
}
