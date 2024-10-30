<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Carbon\Carbon;

class DatetimeAsset implements AssetContract
{
    use BaseAsset;

    public function cast(): Carbon
    {
        return Carbon::parse($this->value);
    }
}
