<?php

namespace Atproto\Lexicons\App\Bsky\Labeler\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method string like
 */
class LabelerViewerState implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.labeler.defs#labelerViewerState';
    }
}
