<?php

namespace Atproto\IPFS\MultiFormats;

use Atproto\Support\Enum;

abstract class MultiCodec
{
    use Enum;

    private const CONSTANTS = [
        'cidv1' => '01',
        'sha2-256' => '12',
        'raw' => '55',
    ];
}
