<?php

namespace Atproto\FieldTypes\Handlers;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;
use Atproto\FieldTypes\Definitions\ObjectTypeDefinition;

class ObjectTypeHandler implements FieldTypeHandlerContract
{
    /**
     * @param  mixed  $value
     * @param  ObjectTypeDefinition  $definition
     */
    public function handle($value, AbstractDefinition $definition)
    {
        $definition = $this->definition($definition);

        if ($value === null && $definition->nullable() === true) {
            return null;
        }

        /** @var class-string $target */
        $target = $definition->target();

        if (! class_exists($target)) {
            throw new \InvalidArgumentException("Class {$target} does not exist.");
        }

        return new $target($value);
    }

    private function definition(ObjectTypeDefinition $definition): ObjectTypeDefinition
    {
        return $definition;
    }
}
