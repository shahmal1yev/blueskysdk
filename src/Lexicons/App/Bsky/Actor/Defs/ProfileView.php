<?php

namespace Atproto\Lexicons\App\Bsky\Actor\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string did
 * @method string handle
 * @method string displayName
 * @method string description
 * @method string avatar
 * @method ProfileAssociated associated
 * @method Carbon indexedAt
 * @method Carbon createdAt
 * @method ViewerState viewer
 * @method Label labels
 */
class ProfileView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'associated' => ProfileAssociated::class,
            'indexedAt' => DatetimeObject::class,
            'createdAt' => DatetimeObject::class,
            'viewer' => ViewerState::class,
            'labels' => Label::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#profileView';
    }
}
