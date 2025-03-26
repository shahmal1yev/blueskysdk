<?php

namespace Atproto\FieldTypes\Definitions;

use Atproto\Contracts\LexiconContract;
use InvalidArgumentException;

class UnionTypeDefinition extends AbstractDefinition
{
    /**
     * @param list<class-string<LexiconContract>> $refs
     */
    public function __construct(array $refs)
    {
        $nonLexiconRefs = array_filter($refs, fn ($ref) => ! in_array(LexiconContract::class, class_implements($ref), true));

        if (! empty($nonLexiconRefs)) {
            throw new InvalidArgumentException(sprintf(
                'Each ref must implement LexiconContract. Invalid: %s',
                implode(', ', $nonLexiconRefs)
            ));
        }

        $this->meta['refs'] = $refs;
        $this->meta['type'] = 'union';
    }

    public function refs()
    {
        return $this->meta['refs'] ?? [];
    }

    public function closed(?bool $closed = null)
    {
        if (is_null($closed)) {
            return $this->meta['closed'] ?? false;
        }

        $this->meta['closed'] = $closed;

        return $this;
    }
}
