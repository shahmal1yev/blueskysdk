<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

/**
 * @method bool muted
 * @method bool blockedBy
 * @method string blocking
 * @method string following
 * @method string followedBy
 * @method MutedByListObject mutedByList
 * @method BlockingByListObject blockingByList
 * @method KnownFollowersObject knownFollowers
 * @method LabelsObject labels
 */
class ViewerObject implements ObjectContract
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
            'mutedByList' => MutedByListObject::class,
            'blockingByList' => BlockingByListObject::class,
            'knownFollowers' => KnownFollowersObject::class,
            'labels' => LabelsObject::class,
        ];
    }
}
