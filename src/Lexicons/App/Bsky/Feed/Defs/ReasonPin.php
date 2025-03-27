<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

class ReasonPin implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#reasonPin';
    }
}
