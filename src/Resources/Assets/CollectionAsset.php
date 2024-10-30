<?php

namespace Atproto\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use GenericCollection\Interfaces\TypeInterface;

trait CollectionAsset
{
    use BaseAsset;

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

    abstract protected function item($data): AssetContract;
    abstract protected function type(): TypeInterface;
}
