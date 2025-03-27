<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Record;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method string uri
 * @method true detached
 */
class ViewDetached implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.record#viewDetached';
    }
}
