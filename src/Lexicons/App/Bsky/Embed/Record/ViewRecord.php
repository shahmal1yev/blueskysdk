<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Record;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Lexicons\App\Bsky\Embed\External\View as ExternalView;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\Record\View as RecordView;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia\View as RecordWithMediaView;
use Atproto\Lexicons\App\Bsky\Embed\Video\View as VideoView;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri
 * @method string cid
 * @method ProfileViewBasic author
 * @method string value
 * @method Label labels
 * @method integer replyCount
 * @method integer repostCount
 * @method integer likeCount
 * @method integer quoteCount
 * @method array<ImagesView|VideoView|ExternalView|RecordView|RecordWithMediaView> embeds
 * @method Carbon indexedAt
 */
class ViewRecord implements DefinitionContract
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
            'labels' => Label::class,
            'author' => ProfileViewBasic::class,
            'embeds' => FieldType::array(FieldType::union([
                ImagesView::class,
                VideoView::class,
                ExternalView::class,
                RecordView::class,
                RecordWithMediaView::class,
            ])),
            'indexedAt' => DatetimeObject::class,
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.record#viewRecord';
    }
}
