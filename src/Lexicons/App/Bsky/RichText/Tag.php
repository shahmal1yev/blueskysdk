<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Tag extends FeatureAbstract
{
    protected function schema(): array
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

    protected function type(): string
    {
        return 'tag';
    }
}
