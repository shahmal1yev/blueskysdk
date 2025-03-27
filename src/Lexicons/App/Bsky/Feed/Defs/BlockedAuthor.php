<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ViewerState;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method string did
 * @method ViewerState viewer
 */
class BlockedAuthor implements DefinitionContract
{
    use Castable;
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'viewer' => ViewerState::class,
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#blockedAuthor';
    }
}