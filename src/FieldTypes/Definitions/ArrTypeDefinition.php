<?php

namespace Atproto\FieldTypes\Definitions;

use Atproto\Contracts\FieldTypes\FieldTypeHandlerContract;

class ArrTypeDefinition extends AbstractDefinition
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
        $this->meta['type'] = 'array';
    }

    public function handler(): FieldTypeHandlerContract
    {
        return $this->handler;
    }

    public function definition(): AbstractDefinition
    {
        return $this->definition;
    }

    /**
     * @return static|string
     */
    public function items(?string $items = null)
    {
        if (is_null($items)) {
            return $this->meta['items'] ?? null;
        }

        $this->meta['items'] = $items;

        return $this;
    }

    public function maxLength(?int $maxLength = null)
    {
        if (is_null($maxLength)) {
            return $this->meta['maxLength'] ?? null;
        }

        $this->meta['maxLength'] = $maxLength;

        return $this;
    }

    public function minLength(?int $minLength = null)
    {
        if (is_null($minLength)) {
            return $this->meta['minLength'] ?? null;
        }

        $this->meta['minLength'] = $minLength;

        return $this;
    }
}
