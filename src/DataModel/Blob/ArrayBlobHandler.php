<?php

namespace Atproto\DataModel\Blob;

use Atproto\Contracts\DataModel\BlobHandler;
use Atproto\Support\Arr;

class ArrayBlobHandler implements BlobHandler
{
    private array $blob;

    public function __construct(array $blob)
    {
        $this->blob = $blob;
    }

    public function size(): int
    {
        return Arr::get($this->blob, 'size');
    }

    public function mimeType(): string
    {
        return Arr::get($this->blob, 'mimeType');
    }

    public function content(): string
    {
        throw new \LogicException("Content does not exists for array blob handler.");
    }

    public function __toString(): string
    {
        return Arr::get($this->blob, 'ref.$link');
    }
}