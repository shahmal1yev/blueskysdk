<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method string repost
 * @method string like
 * @method boolean threadMuted
 * @method boolean replyDisabled
 * @method boolean embeddingDisabled
 * @method boolean pinned
 */
class ViewerState implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#viewerState';
    }
}
