<?php

namespace Atproto\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Lexicons\App\Bsky\Graph\Defs\ListViewBasic;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method string uri
 * @method string cid
 * @method string record
 * @method ListViewBasic lists
 */
class ThreadgateView implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'lists' => ListViewBasic::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'app.bsky.feed.defs#threadgateView';
    }
}
