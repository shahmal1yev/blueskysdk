<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\BaseResource;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;

class KnownFollowersAsset implements AssetContract
{
    use BaseResource, BaseAsset;

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