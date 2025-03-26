<?php

namespace Atproto\Contracts\FieldTypes;

use Atproto\FieldTypes\Definitions\AbstractDefinition;

interface FieldTypeHandlerContract
{
    public function handle($value, AbstractDefinition $definition);
}
