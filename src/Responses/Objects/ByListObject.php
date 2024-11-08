<?php

namespace Atproto\Responses\Objects;

use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri()
 * @method string cid()
 * @method string name()
 * @method object purpose()
 * @method string avatar()
 * @method int listItemCount()
 * @method LabelsObject labels()
 * @method ViewerObject viewer()
 * @method Carbon indexedAt()
 */
trait ByListObject
{
    use BaseObject;
    use Castable;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'labels' => LabelsObject::class,
            'viewer' => ViewerObject::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }
}
