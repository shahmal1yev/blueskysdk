<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\LexiconBuilder;
use Atproto\Contracts\Lexicons\App\Bsky\RichText\FeatureContract;

abstract class FeatureAbstract implements LexiconBuilder
{
    public function jsonSerialize(): array
    {
        return array_merge(
            ['type' => $this->type(),],
            $this->schema()
        );
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    abstract public function schema(): array;
    abstract public function type(): string;
}
