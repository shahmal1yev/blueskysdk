<?php

namespace Atproto\Resources\Assets;

use Atproto\Resources\BaseResource;

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
 * @method AssociatedAsset associated()
 * @method JoinedViaStarterPackAsset joinedViaStarterPack()
 * @method DatetimeAsset indexedAt()
 * @method DatetimeAsset createdAt()
 * @method ViewerAsset viewer()
 * @method LabelsAsset labels()
 */
trait UserAsset
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'associated' => AssociatedAsset::class,
            'indexedAt' => DatetimeAsset::class,
            'joinedViaStarterPack' => JoinedViaStarterPackAsset::class,
            'createdAt' => DatetimeAsset::class,
            'viewer' => ViewerAsset::class,
            'labels' => LabelsAsset::class,
        ];
    }
}