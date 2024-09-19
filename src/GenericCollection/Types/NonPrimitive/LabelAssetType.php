<?php

namespace Atproto\GenericCollection\Types\NonPrimitive;

use Atproto\Resources\Assets\LabelAsset;
use GenericCollection\Interfaces\TypeInterface;

class LabelAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof LabelAsset;
    }
}
