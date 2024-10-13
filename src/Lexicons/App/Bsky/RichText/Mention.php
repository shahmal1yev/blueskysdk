<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Mention extends FeatureAbstract
{
    protected ?string $type = 'mention';

    protected function schema(): array
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

    protected function type(): string
    {
        return 'mention';
    }
}
