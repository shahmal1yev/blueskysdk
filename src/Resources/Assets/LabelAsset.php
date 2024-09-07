<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Resources\BaseResource;

/**
 * @method int ver
 * @method string uri
 * @method string cid
 * @method string val
 * @method bool neg
 * @method string sig
 * @method DatetimeAsset cts
 * @method DatetimeAsset exp
 */
class LabelAsset implements ResourceContract, AssetContract
{
    use BaseResource, BaseAsset;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function casts(): array
    {
        return [
            'cts' => DatetimeAsset::class,
            'exp' => DatetimeAsset::class,
        ];
    }
}