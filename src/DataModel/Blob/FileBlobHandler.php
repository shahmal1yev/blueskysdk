<?php

namespace Atproto\DataModel\Blob;

use Atproto\Contracts\DataModel\BlobHandler;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\IPFS\CID\CID;
use Atproto\IPFS\MultiFormats\MultiBase\MultiBase;
use Atproto\IPFS\MultiFormats\MultiCodec;
use Atproto\Support\FileSupport;

class FileBlobHandler implements BlobHandler
{
    private FileSupport $file;
    private CID $cid;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(FileSupport $file)
    {
        if (! $file->exists()) {
            throw new InvalidArgumentException('$file is not exists');
        }

        if (! $file->isFile()) {
            throw new InvalidArgumentException('$file is not a file');
        }

        if (! $file->isReadable()) {
            throw new InvalidArgumentException('$file is not readable');
        }

        $this->file = $file;

        $this->cid = new CID(
            MultiCodec::get('raw'),
            MultiBase::get('base32'),
            $this->file->getBlob()
        );
    }

    public function size(): int
    {
        return $this->file->getFileSize();
    }

    public function mimeType(): string
    {
        return $this->file->getMimeType();
    }

    public function content(): string
    {
        return $this->file->getBlob();
    }

    public function __toString(): string
    {
        return $this->cid->__toString();
    }
}
