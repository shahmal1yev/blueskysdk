<?php

namespace Atproto\Resources\Assets\Primitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;

class StringAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): string
    {
        return (string) $this->value;
    }
}