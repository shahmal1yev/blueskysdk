<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\SerializableContract;

interface ImageInterface extends SerializableContract
{
    public function jsonSerialize(): array;
    public function alt(string $alt = null);
    public function aspectRatio();
    public function size(): int;
}
