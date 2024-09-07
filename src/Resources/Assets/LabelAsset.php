<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

class LabelAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'cts' => DatetimeAsset::class,
            'exp' => DatetimeAsset::class,
        ];
    }
}