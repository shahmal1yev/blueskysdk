<?php

namespace Atproto\DataModel\Blob;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Support\FileSupport;
use Atproto\Contracts\DataModel\BlobHandler;

class FileBlobHandler implements BlobHandler
{
    private FileSupport $file;

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
}
