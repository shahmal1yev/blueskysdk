<?php

namespace Atproto\Contracts\DataModel;

use Atproto\Contracts\Stringable;

interface BlobHandler extends Stringable
{
    public function size(): int;
    public function mimeType(): string;
    public function content(): string;
}
