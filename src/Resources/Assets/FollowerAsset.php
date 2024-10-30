<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;

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
