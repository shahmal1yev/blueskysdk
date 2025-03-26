<?php

namespace Atproto\FieldTypes\Factories;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\Contracts\FieldTypes\TypePairFactoryContract;
use Atproto\Contracts\LexiconContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\UnionTypeDefinition;
use Atproto\FieldTypes\Handlers\UnionTypeHandler;

class UnionTypePairFactory implements TypePairFactoryContract
{
    public static function handler(): FieldTypeHandlerContract
    {
        return new UnionTypeHandler();
    }

    /**
     * @param list<class-string<LexiconContract>> $refs
     * @return AbstractDefinition
     */
    public static function definition(...$args): AbstractDefinition
    {
        // Accepts list<class-string> via FieldType::union([...])
        // Uses variadic args for interface compatibility, so $args = [[ref1, ref2, ref3]]
        $refs = current($args);

        return new UnionTypeDefinition(self::definitionParameters(...$refs));
    }

    private static function definitionParameters(string ...$refs): array
    {
        $nonLexiconRefs = array_filter($refs, fn ($ref) => ! in_array(LexiconContract::class, class_implements($ref), true));

        if (! empty($nonLexiconRefs)) {
            throw new \InvalidArgumentException(sprintf(
                'Each entry in $refs must be a class-string implementing LexiconContract. The following entries are invalid: %s',
                implode(', ', $nonLexiconRefs)
            ));
        }

        return $refs;
    }
}
