<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\SerializableContract;

interface ByteSliceContract extends SerializableContract
{
    public function start(): int;
    public function end(): int;
}
