<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\GenericCollection\Types\NonPrimitive\LabelAssetType;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class LabelsAsset extends GenericCollection implements AssetContract
{
    use CollectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): AssetContract
    {
        return new LabelAsset($data);
    }

    protected function type(): TypeInterface
    {
        return new LabelAssetType();
    }
}
