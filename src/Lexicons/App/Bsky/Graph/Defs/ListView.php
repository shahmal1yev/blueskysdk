<?php

namespace Atproto\Lexicons\App\Bsky\Graph\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileView;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\FacetsObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method ProfileView creator
 * @method string name
 * @method string purpose
 * @method string description
 * @method FacetsObject descriptionFacets
 * @method string avatar
 * @method integer listItemCount
 * @method Label labels
 * @method ListViewerState viewer
 * @method Carbon indexedAt
 */
class ListView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'creator' => ProfileView::class,
            'descriptionFacets' => FacetsObject::class,
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
        return 'app.bsky.graph.defs#listView';
    }
}
