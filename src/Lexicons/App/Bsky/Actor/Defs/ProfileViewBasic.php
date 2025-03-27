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
 * @method string avatar
 * @method ProfileAssociated associated
 * @method ViewerState viewer
 * @method Label labels
 * @method Carbon createdAt
 */
class ProfileViewBasic implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'associated' => ProfileAssociated::class,
            'viewer' => ViewerState::class,
            'labels' => Label::class,
            'createdAt' => DatetimeObject::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.actor.defs#profileViewBasic';
    }
}
