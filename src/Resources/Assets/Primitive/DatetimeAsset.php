<?php

namespace Atproto\Resources\Assets\Primitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\BaseAsset;
use Carbon\Carbon;

class DatetimeAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): Carbon
    {
        return Carbon::parse($this->value);
    }
}