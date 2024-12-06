<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Closure;
use GenericCollection\GenericCollection;

class PostsObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    protected function item($data): ObjectContract
    {
        return new PostObject($data);
    }

    protected function type(): Closure
    {
        return fn ($value): bool => $value instanceof PostObject;
    }
}
