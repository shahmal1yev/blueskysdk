<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Exceptions\BlueskyException;

trait Serializable
{
    public function __toString(): string
    {
        return json_encode($this);
    }

    /**
     * @throws BlueskyException
     */
    public function jsonSerialize(): array
    {
        throw new BlueskyException(sprintf(
            "The %s class must implement JsonSerializable to support JSON serialization.",
            self::class
        ));
    }
}
