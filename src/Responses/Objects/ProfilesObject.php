<?php

namespace Atproto\Responses\Objects;

use Atproto\Collections\Types\NonPrimitive\ProfileAssetType;
use Atproto\Contracts\Resources\ObjectContract;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class ProfilesObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): ObjectContract
    {
        return new ProfileObject($data);
    }

    protected function type(): TypeInterface
    {
        return new ProfileAssetType();
    }
}
