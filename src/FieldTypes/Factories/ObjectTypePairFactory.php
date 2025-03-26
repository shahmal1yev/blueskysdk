<?php

namespace Atproto\FieldTypes\Factories;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\Contracts\FieldTypes\TypePairFactoryContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;
use Atproto\FieldTypes\Handlers\ObjectTypeHandler;

class ObjectTypePairFactory implements TypePairFactoryContract
{
    public static function handler(): FieldTypeHandlerContract
    {
        return new ObjectTypeHandler();
    }

    /**
     * @param class-string $target
     * @return AbstractDefinition
     */
    public static function definition(...$args): AbstractDefinition
    {
        $parameters = self::definitionParameters(...$args);
        return new ObjectTypeDefinition(...$parameters);
    }

    private static function definitionParameters(string $target): array
    {
        return [$target];
    }
}
