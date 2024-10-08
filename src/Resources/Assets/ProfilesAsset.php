<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\GenericCollection\Types\NonPrimitive\FollowerAssetType;
use Atproto\GenericCollection\Types\NonPrimitive\ProfileAssetType;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class ProfilesAsset extends GenericCollection implements AssetContract
{
    use CollectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function item($data): AssetContract
    {
        return new ProfileAsset($data);
    }

    protected function type(): TypeInterface
    {
        return new ProfileAssetType();
    }
}
