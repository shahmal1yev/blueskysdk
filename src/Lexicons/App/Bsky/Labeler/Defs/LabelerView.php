<?php

namespace Atproto\Lexicons\App\Bsky\Labeler\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileView;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method ProfileView creator
 * @method integer likeCount
 * @method LabelerViewerState viewer
 * @method Carbon indexedAt
 * @method Label labels
 */
class LabelerView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'creator' => ProfileView::class,
            'viewer' => LabelerViewerState::class,
            'indexedAt' => DatetimeObject::class,
            'labels' => Label::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.labeler.defs#labelerView';
    }
}
