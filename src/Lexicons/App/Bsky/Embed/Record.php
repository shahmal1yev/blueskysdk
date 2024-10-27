<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;

class Record implements EmbedInterface
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
            '$type' => $this->type(),
            'record' => $this->ref,
        ];
    }

    public function type(): string
    {
        return 'app.bsky.embed.record';
    }
}
