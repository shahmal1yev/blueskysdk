<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

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
 * @method string did
 * @method ProfileView creator
 * @method string displayName
 * @method string description
 * @method FacetsObject descriptionFacets
 * @method string avatar
 * @method integer likeCount
 * @method boolean acceptsInteractions
 * @method Label labels
 * @method GeneratorViewerState viewer
 * @method string contentMode
 * @method Carbon indexedAt
 */
class GeneratorView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'creator' => ProfileView::class,
            'descriptionFacets' => FacetsObject::class,
            'labels' => Label::class,
            'viewer' => GeneratorViewerState::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#generatorView';
    }
}
