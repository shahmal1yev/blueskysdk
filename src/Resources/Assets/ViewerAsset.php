<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

/**
 * @method bool muted
 * @method bool blockedBy
 * @method string blocking
 * @method string following
 * @method string followedBy
 * @method MutedByListAsset mutedByList
 * @method BlockingByListAsset blockingByList
 * @method KnownFollowersAsset knownFollowers
 * @method LabelsAsset labels
 */
class ViewerAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'mutedByList' => MutedByListAsset::class,
            'blockingByList' => BlockingByListAsset::class,
            'knownFollowers' => KnownFollowersAsset::class,
            'labels' => LabelsAsset::class,
        ];
    }
}