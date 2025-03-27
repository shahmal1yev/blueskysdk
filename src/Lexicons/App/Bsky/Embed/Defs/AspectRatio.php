<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method int width
 * @method int height
 */
class AspectRatio implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.defs#aspectRatio';
    }
}
