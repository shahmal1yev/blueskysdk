<?php

namespace Atproto\Responses\App\Bsky\Actor;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\UserObject;
use Atproto\Responses\Objects\ViewerObject;

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
 * @method AssociatedObject associated()
 * @method JoinedViaStarterPackObject joinedViaStarterPack()
 * @method DatetimeObject indexedAt()
 * @method DatetimeObject createdAt()
 * @method ViewerObject viewer()
 * @method LabelsObject labels()
 */
class GetProfileResponse implements ResponseContract
{
    use UserObject;
}
