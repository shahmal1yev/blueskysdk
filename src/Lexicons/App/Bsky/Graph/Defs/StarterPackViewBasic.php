<?php

namespace Atproto\Lexicons\App\Bsky\Graph\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method string record
 * @method ProfileViewBasic creator
 * @method integer listItemCount
 * @method integer joinedWeekCount
 * @method integer joinedAllTimeCount
 * @method Label labels
 * @method Carbon indexedAt
 */
class StarterPackViewBasic implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'creator' => ProfileViewBasic::class,
            'labels' => Label::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.graph.defs#starterPackViewBasic';
    }
}
