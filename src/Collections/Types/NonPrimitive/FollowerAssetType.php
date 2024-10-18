<?php

namespace Atproto\Collections\Types\NonPrimitive;

use Atproto\Resources\Assets\FollowerAsset;
use GenericCollection\Interfaces\TypeInterface;

class FollowerAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof FollowerAsset;
    }
}
