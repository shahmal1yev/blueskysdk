<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionContract;
use Atproto\Contracts\SerializableContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\Serializable;
use GenericCollection\GenericCollection;

class CaptionCollection extends GenericCollection implements SerializableContract
{
    use Serializable;
    use EmbedCollection;

    private const MAX_SIZE = 20;

    protected function validator(): \Closure
    {
        return function (CaptionContract $caption) {
            if ($this->count() > self::MAX_SIZE) {
                throw new InvalidArgumentException(self::class.' collection exceeds maximum size: ' .self::MAX_SIZE);
            }

            return true;
        };
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
