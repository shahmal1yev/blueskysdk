<?php

namespace Atproto\Responses\App\Bsky\Actor;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\Objects\ProfilesObject;
use Atproto\Responses\BaseResponse;
use Atproto\Traits\Castable;

class GetProfilesResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'profiles' => ProfilesObject::class
        ];
    }
}
