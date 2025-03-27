<?php

namespace Atproto\Lexicons\App\Bsky\Graph\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;

/**
 * @method boolean muted
 * @method string blocked
 */
class ListViewerState implements DefinitionContract
{
    use BaseObject;

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.graph.defs#listViewerState';
    }
}
