<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use JsonSerializable;

class ImageCollection extends GenericCollection implements JsonSerializable
{
    private const MAX_ITEM = 4;
    private const MAX_SIZE  = 1000000;

    public function __construct(iterable $collection = [])
    {
        parent::__construct(fn ($item) => $item instanceof ImageInterface, $collection);
    }

    private function validateLength(): bool
    {
        return ($this->count() <= self::MAX_ITEM);
    }

    private function validateSize(ImageInterface $image): bool
    {
        return $image->size() <= self::MAX_SIZE;
    }

    public function validate($value): bool
    {
        return parent::validate($value)
            && $this->validateSize($value)
            && $this->validateLength();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateWithException($value): void
    {
        parent::validateWithException($value);

        if (! $this->validateLength()) {
            throw new InvalidArgumentException("Image limit exceeded. Maximum allowed images: ".self::MAX_ITEM);
        }

        if (! $this->validateSize($value)) {
            throw new InvalidArgumentException("Image size exceeded. Maximum allowed size: ".self::MAX_SIZE);
        }
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
