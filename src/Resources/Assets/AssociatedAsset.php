<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

/**
 * @method int lists()
 * @method int feedgens()
 * @method int starterPacks()
 * @method bool labeler()
 * @method ChatAsset chat()
 */
class AssociatedAsset implements ResourceContract, AssetContract
{
    use BaseResource;
    use BaseAsset;

    public function __construct($content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'chat' => ChatAsset::class,
        ];
    }
}
