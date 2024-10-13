<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;

trait BlobHandler
{
    protected string $path;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->handle();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function handle(): void
    {
        $this->isFile();
        $this->isReadable();
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isFile(): void
    {
        if (! is_file($this->path)) {
            throw new InvalidArgumentException("$this->path is not a file.");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isReadable(): void
    {
        if (! is_readable($this->path)) {
            throw new InvalidArgumentException("$this->path is not readable.");
        }
    }
}
