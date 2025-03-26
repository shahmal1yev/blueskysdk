<?php

namespace Atproto\Contracts\FieldTypes;

use Atproto\FieldTypes\Definitions\AbstractDefinition;

interface TypePairFactoryContract
{
    public static function handler(): FieldTypeHandlerContract;
    public static function definition(...$args): AbstractDefinition;
}
