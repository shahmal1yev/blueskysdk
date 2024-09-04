<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Carbon\Carbon;

/**
 * @method string uri()
 * @method string cid()
 * @method string name()
 * @method object purpose()
 * @method string avatar()
 * @method int listItemCount()
 * @method LabelsAsset labels()
 * @method ViewerAsset viewer()
 * @method Carbon indexedAt()
 */
class BlockingByListAsset implements ResourceContract, AssetContract
{
    use ByListAsset;
}