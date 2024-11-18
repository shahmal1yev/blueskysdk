<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;
use Carbon\Carbon;

/**
 * @method string uri()
 * @method string cid()
 * @method CreatorObject creator()
 * @method int listItemCount()
 * @method int joinedWeekCount()
 * @method int joinedAllTimeCount()
 * @method LabelsObject labels()
 * @method Carbon indexedAt()
 */
class JoinedViaStarterPackObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    public function __construct($content)
    {
        $this->response = $content;
    }

    public function casts(): array
    {
        return [
            'creator' => CreatorObject::class,
            'labels' => LabelsObject::class,
            'indexedAt' => DatetimeObject::class,
        ];
    }
}
