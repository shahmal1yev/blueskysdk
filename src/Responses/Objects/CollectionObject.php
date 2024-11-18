<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Support\Arr;
use Closure;

trait CollectionObject
{
    use BaseObject;

    public function __construct(array $response)
    {
        $this->value = $response;
        $this->response = $response;

        parent::__construct(
            $this->type(),
            array_map(function (array $data) {
                return $this->item($data)->cast();
            }, $this->value)
        );
    }

    private function parse($offset)
    {
        return Arr::get($this->collection, $offset);
    }

    public function cast(): self
    {
        return $this;
    }

    abstract protected function item($data): ObjectContract;
    abstract protected function type(): Closure;
}
