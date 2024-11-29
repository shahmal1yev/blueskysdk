<?php

namespace Atproto\Responses\App\Bsky\Graph;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\FollowersObject;
use Atproto\Responses\Objects\SubjectObject;
use Atproto\Traits\Castable;

class GetFollowersResponse implements ResponseContract
{
    use BaseResponse;
    use BaseObject;
    use Castable;

    public function __construct(ResponseContract $response)
    {
        $this->response = $response;
    }

    protected function casts(): array
    {
        return [
            'followers' => FollowersObject::class,
            'subject' => SubjectObject::class,
        ];
    }
}
