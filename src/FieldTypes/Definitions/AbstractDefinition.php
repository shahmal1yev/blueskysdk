<?php

namespace Atproto\FieldTypes\Definitions;

use JsonSerializable;

abstract class AbstractDefinition implements JsonSerializable
{
    protected array $meta = [];

    public function metadata(): array
    {
        return array_filter($this->meta);
    }

    public function jsonSerialize(): array
    {
        return $this->metadata();
    }

    public function type(): string
    {
        return $this->meta['type'];
    }

    /**
     * @return static|?string
     */
    public function description(?string $description)
    {
        if (is_null($description)) {
            return $this->meta['description'] ?? null;
        }

        $this->meta['description'] = $description;

        return $this;
    }
}
