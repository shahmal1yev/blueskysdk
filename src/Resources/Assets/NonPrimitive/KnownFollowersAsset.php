<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;

class KnownFollowersAsset implements AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'followers' => FollowersAsset::class
        ];
    }
}