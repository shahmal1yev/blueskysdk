<?php

namespace Atproto\Responses\App\Bsky\Feed;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\PostsObject;
use Atproto\Traits\Castable;

/**
 * @method string cursor
 * @method integer hitsTotal
 * @method PostsObject posts
 */
class SearchPostsResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'posts' => PostsObject::class,
        ];
    }
}
