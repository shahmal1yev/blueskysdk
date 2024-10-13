<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;
use Atproto\Lexicons\App\Bsky\Embed\Blob;
use JsonSerializable;

interface CaptionInterface extends JsonSerializable, Stringable
{
    public function lang(string $lang = null);
    public function file(Blob $file = null);
}
