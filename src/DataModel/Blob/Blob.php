<?php

namespace Atproto\DataModel\Blob;

use Atproto\Contracts\DataModel\BlobContract;
use Atproto\Contracts\DataModel\BlobHandler;
use Atproto\IPFS\CID\CID;
use Atproto\MultiFormats\MultiBase\MultiBase;
use Atproto\MultiFormats\MultiCodec;
use Atproto\Support\FileSupport;

class Blob implements BlobContract
{
    private BlobHandler $handler;
    private CID $cid;

    private function __construct(BlobHandler $handler)
    {
        $this->handler = $handler;
        $this->cid = new CID(
            MultiCodec::get('raw'),
            MultiBase::get('base32'),
            $handler->content()
        );
    }

    public static function viaBinary(string $binary): BlobContract
    {
        return new self(new BinaryBlobHandler($binary));
    }

    public static function viaFile(FileSupport $file): BlobContract
    {
        return new self(new FileBlobHandler($file));
    }

    public function size(): int
    {
        return $this->handler->size();
    }

    public function mimeType(): string
    {
        return $this->handler->mimeType();
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    public function link(): string
    {
        return $this->cid->__toString();
    }

    public function jsonSerialize(): array
    {
        return [
            '$type' => 'blob',
            'ref' => [
                '$link' => $this->link()
            ],
            'mimeType' => $this->mimeType(),
            'size' => $this->size(),
        ];
    }
}
