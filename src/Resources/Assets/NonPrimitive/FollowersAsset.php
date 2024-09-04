<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;

class FollowersAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'follower' => FollowerAsset::class
        ];
    }
}