<?php

namespace Atproto\Collections\Types\NonPrimitive;

use Atproto\Responses\Objects\ProfileObject;
use GenericCollection\Interfaces\TypeInterface;

class ProfileAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof ProfileObject;
    }
}
