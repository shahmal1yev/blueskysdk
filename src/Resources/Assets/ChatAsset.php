<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

/**
 * @method string allowingIncoming()
 */
class ChatAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): self
    {
        return $this;
    }

    protected function casts(): array
    {
        return [];
    }
}