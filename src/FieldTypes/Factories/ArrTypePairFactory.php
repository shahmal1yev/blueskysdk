<?php

namespace Atproto\FieldTypes\Factories;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\Contracts\FieldTypes\TypePairFactoryContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ArrTypeDefinition;
use Atproto\FieldTypes\Handlers\ArrTypeHandler;

class ArrTypePairFactory implements TypePairFactoryContract
{
    public static function handler(): FieldTypeHandlerContract
    {
        return new ArrTypeHandler();
    }

    /**
     * @param FieldTypeHandlerContract $handler
     * @param AbstractDefinition $definition
     * @return AbstractDefinition
     */
    public static function definition(...$args): AbstractDefinition
    {
        $parameters = self::definitionParameters(...$args);
        return new ArrTypeDefinition(...$parameters);
    }

    private static function definitionParameters(FieldTypeHandlerContract $handler, AbstractDefinition $definition): array
    {
        return [$handler, $definition];
    }
}
