<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;

class SubjectObject implements ResponseContract, ObjectContract
{
    use UserObject;
}
