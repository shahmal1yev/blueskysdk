<?php

namespace Atproto\Resources\App\Bsky\Actor;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\ProfilesAsset;
use Atproto\Resources\BaseResource;
use Atproto\Traits\Castable;

class GetProfilesResource implements ResourceContract
{
    use BaseResource;
    use Castable;

    protected function casts(): array
    {
        return [
            'profiles' => ProfilesAsset::class
        ];
    }
}
