<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class ListObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    /**
     * @param $data
     */
    public function __construct($data)
    {
    }


    protected function casts(): array
    {
        return [
            'labels' => LabelsObject::class,
            'viewer' => ViewerObject::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }
}
