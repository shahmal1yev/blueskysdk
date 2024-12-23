<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class FeedItemObject implements ObjectContract
{
    use BaseObject;
    use Castable;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    protected function casts(): array
    {
        return [
            'post' => PostObject::class,
            'reply' => ReplyObject::class,
            'reason' => ReasonObject::class,
        ];
    }
}