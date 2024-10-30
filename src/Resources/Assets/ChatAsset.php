<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

/**
 * @method string allowingIncoming()
 */
class ChatAsset implements ResourceContract, AssetContract
{
    use BaseResource;
    use BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): self
    {
        return $this;
    }
}
