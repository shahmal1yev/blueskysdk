<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Closure;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;

class FollowersObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): ObjectContract
    {
        return new FollowerObject($data);
    }

    protected function type(): Closure
    {
        return fn ($value): bool => $value instanceof FollowerObject;
    }
}
