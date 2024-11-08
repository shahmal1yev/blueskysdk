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

    public function jsonSerialize(): array
    {
        return ['$type' => sprintf("%s#%s", $this->nsid(), $this->type())] + $this->schema();
    }

    public function nsid(): string
    {
        return 'app.bsky.richtext.facet';
    }

    public function type(): string
    {
        $namespace = static::class;
        $parts = explode('\\', $namespace);

        return strtolower(end($parts));
    }

    abstract protected function schema(): array;
}
