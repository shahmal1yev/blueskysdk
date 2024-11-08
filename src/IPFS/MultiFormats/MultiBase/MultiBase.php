<?php

namespace Atproto\IPFS\MultiFormats\MultiBase;

use Atproto\Contracts\EncoderContract;
use Atproto\IPFS\MultiFormats\MultiBase\Encoders\Base32Encoder;
use Atproto\Support\Enum;

/**
 * @property EncoderContract $value
 */
abstract class MultiBase
{
    use Enum;

    private const CONSTANTS = [
        'base32' => Base32Encoder::class,
    ];

    public static function get(string $name)
    {
        self::validate($name);

        $encoder = self::CONSTANTS[$name];
        $instance = new $encoder();

        return self::instance($name, $instance);
    }
}
