<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\Primitive\DatetimeAsset;

/**
 * @method string did()
 * @method string handle()
 * @method string displayName()
 * @method string avatar()
 * @method AssociatedAsset associated()
 * @method DatetimeAsset createdAt()
 * @method ViewerAsset viewer()
 * @method LabelsAsset labels()
 */
class FollowerAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}