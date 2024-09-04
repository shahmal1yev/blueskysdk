<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;

class LabelsAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'label' => LabelAsset::class
        ];
    }
}