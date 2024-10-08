<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\Lexicons\App\Bsky\RichText\FacetContract;
use Atproto\Exceptions\InvalidArgumentException;

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

    protected function type(): string
    {
        return "link";
    }
}
