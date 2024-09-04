<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;

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