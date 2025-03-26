<?php

namespace Atproto\FieldTypes\CastableFields;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;
use Atproto\FieldTypes\Definitions\AbstractDefinition;

class CastableField
{
    private FieldTypeHandlerContract $handler;
    private AbstractDefinition $definition;

    public function __construct(
        FieldTypeHandlerContract $handler,
        AbstractDefinition $definition
    )
    {
        $this->definition = $definition;
        $this->handler = $handler;
    }

    public function handler(): FieldTypeHandlerContract
    {
        return $this->handler;
    }

    public function definition(): AbstractDefinition
    {
        return $this->definition;
    }

    public function __call(string $name, array $args): self
    {
        $this->definition()->$name(...$args);

        return $this;
    }
}
