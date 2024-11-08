<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Atproto\Lexicons\Traits\Lexicon;

class Record implements EmbedInterface
{
    use Lexicon;

    private StrongRef $ref;

    public function __construct(StrongRef $ref)
    {
        $this->ref = $ref;
    }

    public function jsonSerialize(): array
    {
        return [
            '$type' => $this->nsid(),
            'record' => $this->ref,
        ];
    }
}
