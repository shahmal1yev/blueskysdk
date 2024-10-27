<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;

class SubjectAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}
