<?php

namespace Atproto\FieldTypes\Definitions;

use Atproto\FieldTypes\CastableFields\CastableField;

class ObjectTypeDefinition extends AbstractDefinition
{
    private string $target;

    public function __construct(
        string $target
    )
    {
        $this->target = $target;
        $this->meta['type'] = 'object';
    }

    public function target(): string
    {
        return $this->target;
    }

    public function nullable(?bool $nullable = null)
    {
        if (is_null($nullable)) {
            return $this->meta['nullable'] ?? null;
        }

        $this->meta['nullable'] = $nullable;

        return $this;
    }

    /**
     * @param null|array<string, CastableField> $props
     */
    public function properties(?array $props = null)
    {
        if (is_null($props)) {
            return $this->meta['properties'] ?? [];
        }

        $this->meta['properties'] = $props;

        return $this;
    }

    /**
     * @param null|array<string, CastableField> $props
     */
    public function required(?array $props = null)
    {
        if (is_null($props)) {
            return $this->meta['required'] ?? [];
        }

        $this->meta['required'] = $props;

        return $this;
    }
}
