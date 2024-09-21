<?php

namespace Atproto\GenericCollection\Types\NonPrimitive;

use Atproto\Resources\Assets\ProfileAsset;
use GenericCollection\Interfaces\TypeInterface;

class ProfileAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof ProfileAsset;
    }
}
