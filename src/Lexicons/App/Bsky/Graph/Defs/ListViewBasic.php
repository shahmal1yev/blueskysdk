<?php

namespace Atproto\Lexicons\App\Bsky\Graph\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method string name
 * @method string purpose
 * @method string avatar
 * @method integer listItemCount
 * @method Label labels
 * @method ListViewerState viewer
 * @method Carbon indexedAt
 */
class ListViewBasic implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'labels' => Label::class,
            'viewer' => ListViewerState::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.graph.defs#listViewBasic';
    }
}
