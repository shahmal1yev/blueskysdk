<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;

class SubjectAsset implements ResourceContract, AssetContract
{
    use UserAsset;
}
