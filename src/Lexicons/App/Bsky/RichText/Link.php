<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Link extends FeatureAbstract
{
    public function jsonSerialize(): array
    {
        return [
            "label" => $this->label,
            "uri" => $this->reference,
        ];
    }

    public function __toString(): string
    {
        return $this->label;
    }
}
