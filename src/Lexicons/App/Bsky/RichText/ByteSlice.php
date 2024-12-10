<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\Lexicons\App\Bsky\RichText\ByteSliceContract;
use Atproto\Lexicons\Traits\Serializable;

class ByteSlice implements ByteSliceContract
{
    use Serializable;

    private int $start;
    private int $end;

    private function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function start(): int
    {
        return $this->start;
    }

    public function end(): int
    {
        return $this->end;
    }

    public function jsonSerialize(): array
    {
        return [
            'byteStart' => $this->start(),
            'byteEnd' => $this->end(),
        ];
    }

    public static function viaText(string $text, string $added): ByteSliceContract
    {
        $start = mb_strpos($text, $added);
        $end   = mb_strpos($text, $added) + mb_strlen($added);

        return new self($start, $end);
    }

    public static function viaManual(int $start, int $end): ByteSliceContract
    {
        return new self($start, $end);
    }
}
