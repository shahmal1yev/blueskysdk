<?php

namespace Atproto\Responses\Objects;

use Atproto\Collections\Types\NonPrimitive\FollowerAssetType;
use Atproto\Contracts\Resources\ObjectContract;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class FollowersObject extends GenericCollection implements ObjectContract
{
    use CollectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): ObjectContract
    {
        return new FollowerObject($data);
    }

    protected function type(): TypeInterface
    {
        return new FollowerAssetType();
    }
}
