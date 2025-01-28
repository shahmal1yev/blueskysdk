<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;

/**
 * @method string jobId()
 * @method string did()
 * @method string state()
 * @method integer progress()
 * @method array blob()
 * @method string error()
 * @method string message()
 */
class JobStatusObject implements ObjectContract
{
    use BaseObject;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): ObjectContract
    {
        return $this;
    }
}
