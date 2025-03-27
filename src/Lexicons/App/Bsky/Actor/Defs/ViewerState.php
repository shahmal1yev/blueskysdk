<?php

namespace Atproto\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Graph\Defs\ListViewBasic;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method boolean muted
 * @method ListViewBasic mutedByList
 * @method boolean blockedBy
 * @method string blocking
 * @method ListViewBasic blockingByList
 * @method string following
 * @method string followedBy
 * @method KnownFollowers knownFollowers
 */
class ViewerState implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'mutedByList' => ListViewBasic::class,
            'blockingByList' => ListViewBasic::class,
            'knownFollowers' => KnownFollowers::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#viewerState';
    }
}
