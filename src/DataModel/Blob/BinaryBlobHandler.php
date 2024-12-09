<?php

namespace Atproto\DataModel\Blob;

use Atproto\Contracts\DataModel\BlobHandler;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\IPFS\CID\CID;
use Atproto\IPFS\MultiFormats\MultiBase\MultiBase;
use Atproto\IPFS\MultiFormats\MultiCodec;
use finfo;

class BinaryBlobHandler implements BlobHandler
{
    private string $binary;
    private CID $cid;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $binary)
    {
        if (! $this->isBinary($binary)) {
            throw new InvalidArgumentException('$binary must be a binary');
        }

        $this->binary = $binary;

        $this->cid = new CID(
            MultiCodec::get('raw'),
            MultiBase::get('base32'),
            $this->binary
        );
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

    public function __toString(): string
    {
        return $this->cid->__toString();
    }
}
