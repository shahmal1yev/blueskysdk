<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\Lexicons\App\Bsky\RichText\ByteSliceContract;
use Atproto\Lexicons\Traits\Serializable;

class ByteSlice implements ByteSliceContract
{
    use Serializable;

    private string $text;
    private string $added;

    public function __construct(string $text, string $added)
    {
        $this->text = $text;
        $this->added = $added;
    }

    public function start(): int
    {
        return mb_strpos($this->text, $this->added);
    }

    public function end(): int
    {
        return mb_strpos($this->text, $this->added) + mb_strlen($this->added);
    }

    public function jsonSerialize(): array
    {
        return [
            'byteStart' => $this->start(),
            'byteEnd' => $this->end(),
        ];
    }
}
