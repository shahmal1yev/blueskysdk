<?php

namespace Atproto\Resources\Assets\NonPrimitive;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\Assets\Primitive\BoolAsset;
use Atproto\Resources\Assets\Primitive\IntAsset;
use Atproto\Resources\Assets\Primitive\ObjectAsset;
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

    public function cast(): ResourceContract
    {
        return $this;
    }

    protected function casts(): array
    {
        return [
            'chat' => ChatAsset::class,
        ];
    }
}
