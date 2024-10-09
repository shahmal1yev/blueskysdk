<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;
use JsonSerializable;

interface ImageInterface extends JsonSerializable, Stringable
{
    public function jsonSerialize(): array;
    public function alt(string $alt = null);
    public function aspectRatio();
}
