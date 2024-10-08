<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Lexicons\App\Bsky\Embed\Caption;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use JsonSerializable;

class CaptionCollection extends GenericCollection implements JsonSerializable
{
    private const MAX_SIZE = 20;

    public function __construct(iterable $collection = [])
    {
        parent::__construct(fn ($item) => $item instanceof Caption, $collection);
    }

    public function validate($value): bool
    {
        return parent::validate($value) && $this->validateLength();
    }

    private function validateLength(): bool
    {
        return $this->count() <= self::MAX_SIZE;
    }

    public function validateWithException($value): void
    {
        parent::validateWithException($value);

        if (! $this->validateLength()) {
            throw new InvalidArgumentException("Caption length must be less than or equal " . self::MAX_SIZE);
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
