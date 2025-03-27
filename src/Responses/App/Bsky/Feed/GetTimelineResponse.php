<?php

namespace Atproto\Responses\App\Bsky\Feed;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Feed\Defs\FeedViewPost;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\FeedObject;
use Atproto\Traits\Castable;

/**
 * @method string cursor()
 * @method array<FeedViewPost> feed()
 */
class GetTimelineResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'feed' => FieldType::array(FieldType::object(FeedViewPost::class))
        ];
    }
}
