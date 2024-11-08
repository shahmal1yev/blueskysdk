<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;

/**
 * @method string allowingIncoming()
 */
class ChatObject implements ObjectContract
{
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
