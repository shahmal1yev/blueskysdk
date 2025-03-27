<?php

namespace Atproto\Lexicons\Com\Atproto\Label\Defs;

use Atproto\Contracts\DefinitionContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method int ver
 * @method string src
 * @method string uri
 * @method string cid
 * @method string val
 * @method bool neg
 * @method Carbon cts
 * @method Carbon exp
 * @method string sig
 */
class Label implements DefinitionContract
{
    use Castable;
    use BaseObject;

    protected function casts(): array
    {
        return [
            'cts' => DatetimeObject::class,
            'exp' => DatetimeObject::class,
        ];
    }

    public function __construct($value)
    {
        $this->content = $value;
    }

    public static function nsid(): string
    {
        return 'com.atproto.label.defs#label';
    }
}
