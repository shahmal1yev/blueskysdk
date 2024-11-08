<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\SerializableContract;
use Atproto\DataModel\Blob\Blob;

interface CaptionContract extends SerializableContract
{
    public function lang(string $lang = null);
    public function file(Blob $file = null);
}
