<?php

namespace Atproto\Collections\Types\NonPrimitive;

use Atproto\Responses\Objects\FollowerObject;
use GenericCollection\Interfaces\TypeInterface;

class FollowerAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof FollowerObject;
    }
}
