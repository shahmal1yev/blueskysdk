<?php

namespace Atproto\DataModel\Blob;

use Atproto\Exceptions\InvalidArgumentException;
use finfo;
use Atproto\Contracts\DataModel\BlobHandler;

class BinaryBlobHandler implements BlobHandler
{
    private string $binary;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $binary)
    {
        if (! $this->isBinary($binary)) {
            throw new InvalidArgumentException('$binary must be a binary');
        }

        $this->binary = $binary;
    }

    private function isBinary(string $binary): bool
    {
        return ! mb_check_encoding($binary, 'UTF-8');
    }

    public function size(): int
    {
        return strlen($this->binary);
    }

    public function mimeType(): string
    {
        return (new finfo(FILEINFO_MIME_TYPE))->buffer($this->binary);
    }

    public function content(): string
    {
        return $this->binary;
    }
}
