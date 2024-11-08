<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;

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
class CreatorObject implements ObjectContract
{
    use UserObject;
}
