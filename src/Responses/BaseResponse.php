<?php

namespace Atproto\Responses;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Support\Arr;
use Atproto\Traits\Castable;

trait BaseResponse
{
    protected ResponseContract $content;

    public function __construct(ResponseContract $content)
    {
        $this->content = $content;
    }

    /**
     * @param  string  $name
     * @param  array  $arguments
     *
     * @return mixed
     *
     * @throws BadAssetCallException If the asset is not available on the resource
     */
    public function __call(string $name, array $arguments)
    {
        return $this->get($name);
    }

    /**
     * @param  string  $offset
     *
     * @return mixed
     *
     * @throws BadAssetCallException
     */
    public function get($offset)
    {
        if (! $this->exist($offset)) {
            throw new BadAssetCallException($offset);
        }

        return $this->parse($offset);
    }

    public function exist(string $name): bool
    {
        return $this->content->exist($name);
    }

    private function parse(string $name)
    {
        $value = $this->content->get($name);

        if (in_array(Castable::class, class_uses_recursive(static::class))) {
            /** @var ?ObjectContract $cast */
            $asset = Arr::get($this->casts(), $name);

            if ($asset) {
                $value = (new $asset($value))->cast();
            }
        }

        return $value;
    }
}
