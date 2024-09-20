<?php

namespace Atproto\Resources;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Helpers\Arr;
use Atproto\Traits\Castable;

trait BaseResource
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
     * @param  string  $name
     *
     * @return mixed
     *
     * @throws BadAssetCallException
     */
    public function get(string $name)
    {
        if (! $this->exist($name)) {
            throw new BadAssetCallException($name);
        }

        return $this->parse($name);
    }

    public function exist(string $name): bool
    {
        return Arr::has($this->content, $name);
    }

    private function parse(string $name)
    {
        $value = Arr::get($this->content, $name);

        if (in_array(Castable::class, class_uses_recursive(static::class))) {
            /** @var ?AssetContract $cast */
            $asset = Arr::get($this->casts(), $name);

            if ($asset) {
                $value = (new $asset($value))->cast();
            }
        }

        return $value;
    }
}
