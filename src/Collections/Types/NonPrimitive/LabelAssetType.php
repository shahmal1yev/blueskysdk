<?php

namespace Atproto\Collections\Types\NonPrimitive;

use Atproto\Responses\Objects\LabelObject;
use GenericCollection\Interfaces\TypeInterface;

class LabelAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof LabelObject;
    }
}
