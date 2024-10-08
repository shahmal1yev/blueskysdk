<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\LexiconBuilder;

interface ByteSliceContract extends LexiconBuilder
{
    public function start(): int;
    public function end(): int;
}
