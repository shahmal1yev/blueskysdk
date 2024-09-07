<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use GenericCollection\Exceptions\InvalidArgumentException;

trait BaseAsset
{
    /** @var mixed */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
        $this->cast();
    }
    
    public function cast(): AssetContract
    {
        return $this;
    }

    public function revert()
    {
        return $this->value;
    }
}
