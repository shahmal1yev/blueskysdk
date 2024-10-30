<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;

class ProfileAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}
