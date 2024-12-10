<?php

namespace Atproto\Responses;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Support\Arr;
use Atproto\Traits\Castable;

trait BaseResponse
{
    protected array $content = [];

    public function __construct(array $content)
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
     * Get the value of the specified offset.
     *
     * @param  string  $offset
     *
     * @return mixed
     *
     * @throws BadAssetCallException
     *
     * @deprecated 1.5.0-beta This method is deprecated and will be removed in version 2.x.
     *             Use the `resolve` instead.
     */
    public function get($offset)
    {
        if (! $this->exist($offset)) {
            throw new BadAssetCallException($offset);
        }

        trigger_error(
            sprintf(
                'The method %s::get() is deprecated since version 1.5.0-beta and will be removed in version 2.x. Use %s::resolve() instead.',
                __CLASS__,
                __CLASS__
            ),
            E_USER_DEPRECATED
        );

        return $this->parse($offset);
    }

    public function resolve($name)
    {
        return @$this->get($name);
    }

    /**
     * Checks if the specified key exists in the content.
     *
     * @param  string  $name
     * @return bool
     *
     * @deprecated 1.5.0-beta This method is deprecated and will be removed in version 2.x.
     *             Use the `has` instead.
     */
    public function exist(string $name): bool
    {
        trigger_error(
            sprintf(
                'The method %s::exist() is deprecated since version 1.5.0-beta and will be removed in version 2.0. Use %s::hasKey() instead.',
                __CLASS__,
                __CLASS__
            ),
            E_USER_DEPRECATED
        );

        return Arr::has($this->content, $name);
    }

    public function has(string $name): bool
    {
        return @$this->exist($name);
    }

    private function parse(string $name)
    {
        $value = Arr::get($this->content, $name);

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
