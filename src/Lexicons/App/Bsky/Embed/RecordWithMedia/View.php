<?php

namespace Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Embed\External\View as ExternalView;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\Video\View as VideoView;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method ExternalView record
 * @method ImagesView|ExternalView|ExternalView media
 */
class View implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'record' => ExternalView::class,
            'media' => FieldType::union([
                ImagesView::class,
                VideoView::class,
                ExternalView::class,
            ])
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.recordWithMedia#view';
    }
}
