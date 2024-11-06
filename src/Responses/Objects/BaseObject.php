<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;

trait BaseObject
{
    /** @var mixed */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
        $this->cast();
    }

    public function cast(): ObjectContract
    {
        return $this;
    }

    public function revert()
    {
        return $this->value;
    }
}
