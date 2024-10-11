<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use JsonSerializable;

class ImageCollection extends GenericCollection implements JsonSerializable
{
    use EmbedCollection;

    private const MAX_LENGTH = 4;
    private const MAX_SIZE  = 1000000;

    protected function validator(): \Closure
    {
        return function (ImageInterface $image) {
            if ($this->count() > self::MAX_LENGTH) {
                throw new InvalidArgumentException(self::class.' collection exceeds maximum size: ' .self::MAX_LENGTH);
            }

            if ($image->size() > self::MAX_SIZE) {
                throw new InvalidArgumentException(self::class.' collection only accepts images with size less than '. self::MAX_SIZE);
            }

            return true;
        };
    }
}
