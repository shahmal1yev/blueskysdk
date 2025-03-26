<?php

namespace Atproto\FieldTypes\Handlers;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\Contracts\LexiconContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\UnionTypeDefinition;
use InvalidArgumentException;

class UnionTypeHandler implements FieldTypeHandlerContract
{
    public function handle($value, AbstractDefinition $unionTypeDefinition)
    {
        /** @var UnionTypeDefinition $unionTypeDefinition */
        $unionTypeDefinition = $this->definition($unionTypeDefinition);

        /** @var list<class-string<LexiconContract>> $refs */
        $refs = $unionTypeDefinition->refs();

        $givenNSIDs = array_merge(...array_map(
            fn (string $refClassName) => [$refClassName => $refClassName::nsid()],
            $refs
        ));

        $targetClass = array_search($targetNSID = $value['$type'], $givenNSIDs);

        if ($targetClass !== false) {
            return new $targetClass($value);
        }

        throw new InvalidArgumentException(
            "Unable to resolve {$targetNSID} in given NSIDs: " . implode(', ', $givenNSIDs)
        );
    }

    private function definition(UnionTypeDefinition $definition): UnionTypeDefinition
    {
        return $definition;
    }
}
