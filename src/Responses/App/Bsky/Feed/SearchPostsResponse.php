<?php

namespace Atproto\Responses\App\Bsky\Feed;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Feed\Defs\PostView;
use Atproto\Responses\BaseResponse;
use Atproto\Traits\Castable;

/**
 * @method string cursor
 * @method integer hitsTotal
 * @method array<PostView> posts
 */
class SearchPostsResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'posts' => FieldType::array(FieldType::object(PostView::class)),
        ];
    }
}
