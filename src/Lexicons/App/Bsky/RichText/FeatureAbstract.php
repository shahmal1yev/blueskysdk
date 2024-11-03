<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\LexiconContract;
use Atproto\Lexicons\Traits\Lexicon;

abstract class FeatureAbstract implements LexiconContract
{
    use Lexicon;

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
        return ['type' => $this->nsid()] + $this->schema();
    }

    abstract protected function schema(): array;
}
