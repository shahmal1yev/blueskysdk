<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;

class ProfileObject implements ResponseContract, ObjectContract
{
    use UserObject;
}
