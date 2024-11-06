<?php

namespace Atproto\Responses\Objects;

use Atproto\Collections\Types\NonPrimitive\LabelAssetType;
use Atproto\Contracts\Resources\ObjectContract;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class LabelsObject extends GenericCollection implements ObjectContract
{
    use CollectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): ObjectContract
    {
        return new LabelObject($data);
    }

    protected function type(): TypeInterface
    {
        return new LabelAssetType();
    }
}
