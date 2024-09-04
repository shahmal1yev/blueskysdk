<?php

namespace Atproto\Resources\Assets\Primitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;

class ObjectAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): object
    {
        return (object) $this->value;
    }
}