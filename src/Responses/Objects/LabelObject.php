<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

/**
 * @method int ver
 * @method string uri
 * @method string cid
 * @method string val
 * @method bool neg
 * @method string sig
 * @method DatetimeObject cts
 * @method DatetimeObject exp
 */
class LabelObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    public function __construct(array $content)
    {
        $this->response = $content;
    }

    public function casts(): array
    {
        return [
            'cts' => DatetimeObject::class,
            'exp' => DatetimeObject::class,
        ];
    }
}
