<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;
use Carbon\Carbon;

/**
 * @method string uri()
 * @method string cid()
 * @method CreatorAsset creator()
 * @method int listItemCount()
 * @method int joinedWeekCount()
 * @method int joinedAllTimeCount()
 * @method LabelsAsset labels()
 * @method Carbon indexedAt()
 */
class JoinedViaStarterPackAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'creator' => CreatorAsset::class,
            'labels' => LabelsAsset::class,
            'indexedAt' => DatetimeAsset::class,
        ];
    }
}