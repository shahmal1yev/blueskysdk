<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\Assets\Primitive\DatetimeAsset;
use Atproto\Resources\BaseResource;

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
    use BaseResource, BaseAsset;

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