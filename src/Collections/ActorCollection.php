<?php

namespace Atproto\Collections;

use GenericCollection\GenericCollection;
use GenericCollection\Types\Primitive\StringType;

class ActorCollection extends GenericCollection
{
    public function __construct(iterable $collection = [])
    {
        parent::__construct(new StringType, $collection);
    }
}