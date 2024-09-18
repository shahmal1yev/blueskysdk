<?php

namespace Atproto\Resources\Assets;

use Atproto\Resources\BaseResource;
use Atproto\Traits\Castable;
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
trait ByListAsset
{
    use BaseResource;
    use BaseAsset;
    use Castable;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'labels' => LabelsAsset::class,
            'viewer' => ViewerAsset::class,
            'indexedAt' => DatetimeAsset::class,
        ];
    }
}
