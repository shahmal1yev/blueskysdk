<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Record;

use Atproto\Contracts\DefinitionContract;
use Atproto\FieldTypes\FieldType;
use Atproto\Lexicons\App\Bsky\Feed\Defs\GeneratorView;
use Atproto\Lexicons\App\Bsky\Graph\Defs\ListView;
use Atproto\Lexicons\App\Bsky\Graph\Defs\StarterPackViewBasic;
use Atproto\Lexicons\App\Bsky\Labeler\Defs\LabelerView;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method ViewRecord|ViewNotFound|ViewBlocked|ViewDetached|GeneratorView|ListView|LabelerView|StarterPackViewBasic record
 */
class View implements DefinitionContract
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
            'record' => FieldType::union([
                ViewRecord::class,
                ViewNotFound::class,
                ViewBlocked::class,
                ViewDetached::class,
                GeneratorView::class,
                ListView::class,
                LabelerView::class,
                StarterPackViewBasic::class,
            ])
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.record#view';
    }
}
