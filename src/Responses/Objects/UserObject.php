<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Traits\Castable;

/**
 * @method string did()
 * @method string handle()
 * @method string displayName()
 * @method string description()
 * @method string avatar()
 * @method string banner()
 * @method int followersCount()
 * @method int followsCount()
 * @method int postsCount()
 * @method AssociatedObject associated()
 * @method JoinedViaStarterPackObject joinedViaStarterPack()
 * @method DatetimeObject indexedAt()
 * @method DatetimeObject createdAt()
 * @method ViewerObject viewer()
 * @method LabelsObject labels()
 */
trait UserObject
{
    use BaseObject;
    use Castable;

    public function __construct(ResponseContract $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'associated' => AssociatedObject::class,
            'indexedAt' => DatetimeObject::class,
            'joinedViaStarterPack' => JoinedViaStarterPackObject::class,
            'createdAt' => DatetimeObject::class,
            'viewer' => ViewerObject::class,
            'labels' => LabelsObject::class,
        ];
    }
}
