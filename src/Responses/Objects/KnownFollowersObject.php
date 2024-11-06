<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Responses\BaseResponse;
use Atproto\Traits\Castable;

class KnownFollowersObject implements ObjectContract
{
    use BaseResponse;
    use BaseObject;
    use Castable;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'followers' => FollowersObject::class,
        ];
    }
}
