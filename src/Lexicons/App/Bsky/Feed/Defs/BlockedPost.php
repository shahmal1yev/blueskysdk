<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method string uri
 * @method true blocked
 * @method BlockedAuthor author
 */
class BlockedPost implements DefinitionContract
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
            'author' => BlockedAuthor::class,
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#blockedPost';
    }
}
