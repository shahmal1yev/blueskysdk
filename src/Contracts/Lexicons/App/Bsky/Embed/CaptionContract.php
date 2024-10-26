<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;
use Atproto\DataModel\Blob\Blob;
use JsonSerializable;

interface CaptionContract extends JsonSerializable, Stringable
{
    public function lang(string $lang = null);
    public function file(Blob $file = null);
}
