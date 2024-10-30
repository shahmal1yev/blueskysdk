<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Atproto\Lexicons\Traits\Endpoint;
use Atproto\Lexicons\Traits\Serializable;

class Record implements EmbedInterface
{
    use Serializable;

    private StrongRef $ref;

    public function __construct(StrongRef $ref)
    {
        $this->ref = $ref;
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
