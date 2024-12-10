<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Traits\Castable;

class RecordObject implements ObjectContract
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
            'createdAt' => DatetimeObject::class,
            'embed' => EmbedObject::class,
            'facets' => FacetsObject::class,
            'author' => AuthorObject::class,
        ];
    }
}
