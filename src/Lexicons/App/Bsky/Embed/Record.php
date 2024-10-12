<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use JsonSerializable;

class Record implements JsonSerializable, Stringable
{
    private StrongRef $ref;

    public function __construct(StrongRef $ref)
    {
        $this->ref = $ref;
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    public function jsonSerialize(): array
    {
        return [
            'record' => $this->ref,
        ];
    }
}
