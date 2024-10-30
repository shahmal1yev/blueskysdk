<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;

/**
 * @method string did()
 * @method string handle()
 * @method string displayName()
 * @method string description()
 * @method string avatar()
 * @method string banner()
 * @method int followersCount()
 * @method int followsCount()
 * @method int postsCount()
 * @method AssociatedAsset associated()
 * @method JoinedViaStarterPackAsset joinedViaStarterPack()
 * @method DatetimeAsset indexedAt()
 * @method DatetimeAsset createdAt()
 * @method ViewerAsset viewer()
 * @method LabelsAsset labels()
 */
class CreatorAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}
