<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\BaseResponse;
use Atproto\Traits\Castable;

/**
 * @method int lists()
 * @method int feedgens()
 * @method int starterPacks()
 * @method bool labeler()
 * @method ChatObject chat()
 */
class AssociatedObject implements ResponseContract, ObjectContract
{
    use BaseResponse;
    use BaseObject;
    use Castable;

    public function __construct($content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'chat' => ChatObject::class,
        ];
    }
}
