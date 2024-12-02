<?php

namespace Atproto\Responses;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Lexicons\Traits\ResponseTrait;
use Atproto\Support\Arr;
use Atproto\Traits\Castable;
use Psr\Http\Message\ResponseInterface;

trait BaseResponse
{
    use ResponseTrait;

    protected ResponseContract $response;

    public function __construct(ResponseContract $response)
    {
        $this->response = $response;
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
        return $this->response->exist($name);
    }

    private function parse(string $name)
    {
        $value = $this->response->get($name);

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
