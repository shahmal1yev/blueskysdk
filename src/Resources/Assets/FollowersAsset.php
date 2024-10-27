<?php

namespace Atproto\Resources\Assets;

use Atproto\Collections\Types\NonPrimitive\FollowerAssetType;
use Atproto\Contracts\HTTP\Resources\AssetContract;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class FollowersAsset extends GenericCollection implements AssetContract
{
    use CollectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): AssetContract
    {
        return new FollowerAsset($data);
    }

    protected function type(): TypeInterface
    {
        return new FollowerAssetType();
    }
}
