<?php

namespace Atproto\IPFS\MultiFormats;

abstract class MultiHash
{
    private const MULTICODEC_NAME__HASH_ALGO = [
        'sha2-256' => 'sha256',
    ];

    public static function generate(string $hashMulticodecName, string $content): string
    {
        $hash = hash(
            self::MULTICODEC_NAME__HASH_ALGO[MultiCodec::get($hashMulticodecName)->name],
            $content,
            true
        );

        return sprintf(
            "%s%s%s",
            encode_varint(intval(MultiCodec::get($hashMulticodecName)->value, 16)),
            encode_varint(strlen($hash)),
            $hash
        );
    }
}
