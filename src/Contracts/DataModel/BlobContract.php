<?php

namespace Atproto\Contracts\DataModel;

use Atproto\Contracts\Stringable;
use Atproto\Support\FileSupport;

interface BlobContract extends Stringable, \JsonSerializable
{
    public static function viaFile(FileSupport $file): BlobContract;
    public static function viaBinary(string $binary): BlobContract;

    public function size(): int;
    public function mimeType(): string;
    public function link(): string;
}
