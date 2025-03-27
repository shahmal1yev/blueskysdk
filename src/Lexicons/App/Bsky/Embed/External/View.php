<?php

namespace Atproto\Lexicons\App\Bsky\Embed\External;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;

/**
 * @method External external
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
            'external' => External::class
        ];
    }

    public static function nsid(): string
    {
        return 'app.bsky.embed.external#view';
    }
}
