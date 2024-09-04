<?php

namespace Atproto\Resources;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Helpers\Arr;

trait BaseResource
{
    protected array $content = [];

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $name
     *
     * @throws BadAssetCallException If the asset is not available on the resource
     */
    public function __call(string $name, array $arguments)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     *
     * @throws BadAssetCallException If the asset is not available on the resource
     */
    private function get(string $name)
    {
        throw_if(! $this->exists($name), new BadAssetCallException($name));

        $asset = $this->parse($name);

        return $asset;
    }

    private function exists(string $name): bool
    {
        return Arr::has($this->content, $name);
    }

    private function parse(string $name)
    {
        $value = Arr::get($this->content, $name);

        /** @var ?AssetContract $cast */
        $asset = Arr::get($this->casts(), $name);

        if ($asset) {
            $value = (new $asset($value))->cast();
        }

        return $value;
    }

    abstract protected function casts(): array;
}