<?php

namespace Atproto\Resources\App\Bsky\Actor;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\AssociatedAsset;
use Atproto\Resources\Assets\DatetimeAsset;
use Atproto\Resources\Assets\JoinedViaStarterPackAsset;
use Atproto\Resources\Assets\LabelsAsset;
use Atproto\Resources\Assets\UserAsset;
use Atproto\Resources\Assets\ViewerAsset;

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
class GetProfileResource implements ResourceContract
{
    use UserAsset;
}