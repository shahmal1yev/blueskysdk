<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\Lexicons\Traits\Lexicon;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;

class ImageCollection extends GenericCollection implements EmbedInterface, MediaContract
{
    use Lexicon;
    use EmbedCollection;

    public function __construct(iterable $collection = [])
    {
        $this->collection = $collection;

        try {
            parent::__construct($this->validator(), $collection);
        } catch (\TypeError|\GenericCollection\Exceptions\InvalidArgumentException $e) {
            $this->throw($e);
        }
    }

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

    public function jsonSerialize(): array
    {
        return [
            'images' => $this->toArray(),
            '$type' => $this->nsid(),
        ];
    }

    public function nsid(): string
    {
        return 'app.bsky.embed.images';
    }
}
