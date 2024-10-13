<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionInterface;
use Atproto\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use JsonSerializable;

class CaptionCollection extends GenericCollection implements JsonSerializable
{
    use EmbedCollection;
    private const MAX_SIZE = 20;

    protected function validator(): \Closure
    {
        return function (CaptionInterface $caption) {
            if ($this->count() > self::MAX_SIZE) {
                throw new InvalidArgumentException(self::class.' collection exceeds maximum size: ' .self::MAX_SIZE);
            }

            return true;
        };
    }
}
