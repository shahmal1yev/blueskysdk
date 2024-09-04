<?php

namespace Atproto\Resources\Assets\Primitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;

class IntAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): int
    {
        return (int) $this->value;
    }
}
