<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use JsonSerializable;

interface ImageInterface extends JsonSerializable
{
    public function jsonSerialize(): array;
    public function alt(string $alt = null);
    public function aspectRatio();
}
