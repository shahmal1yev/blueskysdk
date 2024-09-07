<?php

namespace Atproto\API\Traits;

use Atproto\Contracts\HTTP\Resources\ResourceContract;

trait ResourceSupport
{
    /**
     * @param array $response
     * @return ResourceContract
     */
    abstract public function resource(array $response): ResourceContract;
}
