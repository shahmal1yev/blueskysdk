<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Images;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Embed\Defs\AspectRatio;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method string thumb
 * @method string fullsize
 * @method string alt
 * @method AspectRatio aspectRatio
 */
class ViewImage implements DefinitionContract
{
    use Castable;
    use BaseObject;

    public function __construct(array $value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'aspectRatio' => AspectRatio::class,
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.images#viewImage';
    }
}
