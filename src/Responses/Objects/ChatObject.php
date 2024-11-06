<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;

/**
 * @method string allowingIncoming()
 */
class ChatObject implements ResponseContract, ObjectContract
{
    use BaseResponse;
    use BaseObject;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): self
    {
        return $this;
    }
}
