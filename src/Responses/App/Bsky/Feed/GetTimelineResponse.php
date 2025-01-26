<?php

namespace Atproto\Responses\App\Bsky\Feed;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\FeedObject;
use Atproto\Traits\Castable;

/**
 * @method string cursor()
 * @method FeedObject feed()
 */
class GetTimelineResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;


    protected function casts(): array
    {
        return [
            'feed' => FeedObject::class
        ];
    }
}
