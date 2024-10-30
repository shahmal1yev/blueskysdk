<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Resources\BaseResource;
use Atproto\Traits\Castable;

class KnownFollowersAsset implements AssetContract
{
    use BaseResource;
    use BaseAsset;
    use Castable;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'followers' => FollowersAsset::class,
        ];
    }
}
