<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Lexicons\App\Bsky\Embed\Video\View as VideoView;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\External\View as ExternalView;
use Atproto\Lexicons\App\Bsky\Embed\Record\View as RecordView;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia\View as RecordWithMediaView;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Lexicons\Traits\Lexicon;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method ProfileViewBasic author
 * @method string record
 * @method Carbon indexedAt
 * @method ImagesView|VideoView|ExternalView|RecordView|RecordWithMediaView embed
 * @method int replyCount
 * @method int repostCount
 * @method int likeCount
 * @method int quoteCount
 * @method ViewerState viewer
 * @method Label labels
 * @method ThreadgateView threadgate
 */
class PostView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    protected function casts(): array
    {
        return [
            'author' => ProfileViewBasic::class,
            'indexedAt' => DatetimeObject::class,
            'embed' => FieldType::union([
                ImagesView::class,
                VideoView::class,
                ExternalView::class,
                RecordView::class,
                RecordWithMediaView::class,
            ]),
            'viewer' => ViewerState::class,
            'labels' => Label::class,
            'threadgate' => ThreadgateView::class
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#postView';
    }
}
