<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;

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
class FollowerObject implements ResponseContract, ObjectContract
{
    use UserObject;
}
