<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Stringable;

class File implements Stringable
{
    use BlobHandler;

    public function size(): int
    {
        return filesize($this->path);
    }

    public function type(): string
    {
        return mime_content_type($this->path);
    }

    public function blob(): string
    {
        return file_get_contents($this->path);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function __toString(): string
    {
        return $this->blob();
    }
}
