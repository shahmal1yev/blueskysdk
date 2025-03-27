<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method PostView post
 * @method ReplyRef replyRef
 * @method ReasonRepost|ReasonPin reason
 */
class FeedViewPost implements DefinitionContract
{
    use BaseObject;
    use Castable;

    public function __construct($value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'post' => PostView::class,
            'replyRef' => ReplyRef::class,
            'reason' => FieldType::union([
                ReasonRepost::class,
                ReasonPin::class,
            ]),
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#feedViewPost';
    }
}
