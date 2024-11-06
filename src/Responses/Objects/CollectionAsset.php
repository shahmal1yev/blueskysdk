<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use GenericCollection\Interfaces\TypeInterface;

trait CollectionAsset
{
    use BaseObject;

    public function __construct(array $content)
    {
        $this->value = $content;

        parent::__construct(
            $this->type(),
            array_map(function (array $data) {
                return $this->item($data)->cast();
            }, $this->value)
        );
    }

    public function cast(): self
    {
        return $this;
    }

    abstract protected function item($data): ObjectContract;
    abstract protected function type(): TypeInterface;
}
