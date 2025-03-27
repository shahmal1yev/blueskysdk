<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Video;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Embed\Defs\AspectRatio;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method string cid
 * @method string playlist
 * @method string thumbnail
 * @method string alt
 * @method AspectRatio aspectRatio
 */
class View implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'aspectRatio' => AspectRatio::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.video#view';
    }
}
