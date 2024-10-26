<?php

namespace Atproto\Contracts\DataModel;

interface BlobHandler
{
    public function size(): int;
    public function mimeType(): string;
    public function content(): string;
}
