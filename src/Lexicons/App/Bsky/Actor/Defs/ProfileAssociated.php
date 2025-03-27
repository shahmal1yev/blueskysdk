<?php

namespace Atproto\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method int lists
 * @method int feedgens
 * @method int starterPack
 * @method boolean labeler
 * @method ProfileAssociatedChat chat
 */
class ProfileAssociated implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'chat' => ProfileAssociatedChat::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#profileAssociated';
    }
}
