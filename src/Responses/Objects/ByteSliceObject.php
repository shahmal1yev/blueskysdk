<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Lexicons\App\Bsky\RichText\ByteSlice;

class ByteSliceObject implements ObjectContract
{
    use BaseObject;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): ByteSlice
    {
        # Note: The order of parameters differs between the ByteSlice factory methods and the endpoint response.
        # - ByteSlice factory methods expect parameters in the order: start, end.
        # - The endpoint response provides parameters in the order: end, start.

        return ByteSlice::viaManual(...array_reverse($this->content));
    }
}
