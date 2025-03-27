<?php

namespace Atproto\Contracts\Resources;

use Atproto\Contracts\Stringable;
use Atproto\Exceptions\Resource\BadAssetCallException;

interface ResponseContract extends Stringable, \JsonSerializable
{
    /**
     * @param  string  $name
     * @return mixed
     *
     * @throws BadAssetCallException If the asset does not exist on resource.
     */
    public function get($offset);

    /**
     * @param  string  $name
     * @return bool
     */
    public function exist(string $name): bool;

    public function __toString(): string;

    #[\ReturnTypeWillChange]
    public function jsonSerialize();
}
