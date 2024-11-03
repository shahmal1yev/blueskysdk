<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Tag extends FeatureAbstract
{
    public function jsonSerialize(): array
    {
        return [
            "label" => "#$this->label",
            "tag" => $this->reference,
        ];
    }

    public function __toString(): string
    {
        return "#$this->label";
    }
}
