<?php

namespace Atproto\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method string allowIncoming
 */
class ProfileAssociatedChat implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#profileAssociatedChat';
    }
}
