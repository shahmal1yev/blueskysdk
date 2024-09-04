<?php

namespace Atproto\Resources\Assets\Primitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;

class BoolAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): bool
    {
        return (bool) $this->value;
    }
}