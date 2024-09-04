<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\ResourceContract;

trait BaseAsset
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function cast(): ResourceContract
    {
        return $this;
    }

    public function revert()
    {
        return $this->value;
    }
}
