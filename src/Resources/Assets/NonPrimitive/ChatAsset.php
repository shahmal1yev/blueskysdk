<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;

/**
 * @method string allowingIncoming()
 */
class ChatAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct($content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
        ];
    }
}