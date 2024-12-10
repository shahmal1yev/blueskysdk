<?php

namespace Atproto\Contracts\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\SerializableContract;

interface ByteSliceContract extends SerializableContract
{
    public static function viaText(string $text, string $added): self;
    public static function viaManual(int $start, int $end): self;
    public function start(): int;
    public function end(): int;
}
