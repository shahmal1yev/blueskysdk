<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;

class ProfileAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}
