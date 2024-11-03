<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Mention extends FeatureAbstract
{
    public function jsonSerialize(): array
    {
        return [
            'label' => "@$this->label",
            'did' => $this->reference,
        ];
    }

    public function __toString(): string
    {
        return "@$this->label";
    }
}
