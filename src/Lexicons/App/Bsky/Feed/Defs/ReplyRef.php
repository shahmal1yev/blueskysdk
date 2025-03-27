<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method PostView|NotFoundPost|BlockedPost root
 * @method PostView|NotFoundPost|BlockedPost parent
 * @method ProfileViewBasic grandparentAuthor
 */
class ReplyRef implements DefinitionContract
{
    use BaseObject;
    use Castable;

    public function __construct($value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'root' => FieldType::union([
                PostView::class,
                NotFoundPost::class,
                BlockedPost::class,
            ]),
            'parent' => FieldType::union([
                PostView::class,
                NotFoundPost::class,
                BlockedPost::class,
            ]),
            'grandparentAuthor' => ProfileViewBasic::class,
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#replyRef';
    }
}
