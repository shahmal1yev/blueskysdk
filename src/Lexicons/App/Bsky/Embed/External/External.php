<?php

namespace Atproto\Lexicons\App\Bsky\Embed\External;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method string uri
 * @method string title
 * @method string description
 * @method string thumb
 */
class External implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.external#external';
    }
}
