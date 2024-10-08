<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;
use JsonSerializable;

interface VideoInterface extends JsonSerializable
{
    public function jsonSerialize(): array;
    public function captions(CaptionCollection $captions = null);
    public function alt(string $alt = null);
    public function aspectRatio();
}
