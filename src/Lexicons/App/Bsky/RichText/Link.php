<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Link extends FeatureAbstract
{
    protected function schema(): array
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
