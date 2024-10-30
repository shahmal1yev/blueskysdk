<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\LexiconContract;

abstract class FeatureAbstract implements LexiconContract
{
    protected string $reference;
    protected string $label;

    public function __construct(string $reference, string $label = null)
    {
        if (is_null($label)) {
            $label = $reference;
        }

        $this->reference = $reference;
        $this->label = $label;
    }

    final public function jsonSerialize(): array
    {
        return ['type' => $this->type()] + $this->schema();
    }

    abstract protected function type(): string;

    abstract protected function schema(): array;
}
