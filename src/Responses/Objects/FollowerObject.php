<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;

/**
 * @method string did()
 * @method string handle()
 * @method string displayName()
 * @method string avatar()
 * @method AssociatedObject associated()
 * @method DatetimeObject createdAt()
 * @method ViewerObject viewer()
 * @method LabelsObject labels()
 */
class FollowerObject implements ObjectContract
{
    use UserObject;
}
