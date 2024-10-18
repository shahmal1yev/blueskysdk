<?php

namespace Atproto\Resources\App\Bsky\Graph;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\Assets\FollowersAsset;
use Atproto\Resources\BaseResource;
use Atproto\Traits\Castable;

class GetFollowersResource implements ResourceContract
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
