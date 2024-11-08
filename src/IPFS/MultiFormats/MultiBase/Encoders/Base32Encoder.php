<?php

namespace Atproto\IPFS\MultiFormats\MultiBase\Encoders;

use Atproto\Contracts\EncoderContract;
use Atproto\Exceptions\InvalidArgumentException;

class Base32Encoder implements EncoderContract
{
    use MultiBaseSupport;

    public function encode($data): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz234567';
        $encoded = '';
        $binaryString = '';

        foreach (str_split($data) as $char) {
            $binaryString .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binaryString, 5);
        foreach ($chunks as $chunk) {
            $encoded .= $alphabet[bindec(str_pad($chunk, 5, '0', STR_PAD_RIGHT))];
        }

        return $this->prefixed($encoded);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function decode($data): string
    {
        $data = $this->unprefixed($data);

        $alphabet = 'abcdefghijklmnopqrstuvwxyz234567';
        $binaryString = '';
        $decoded = '';

        $data = rtrim($data, '=');

        foreach (str_split($data) as $char) {
            $position = strpos($alphabet, $char);
            if ($position === false) {
                throw new InvalidArgumentException("Invalid character found in Base32 string.");
            }
            $binaryString .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binaryString, 8);
        foreach ($chunks as $chunk) {
            if (strlen($chunk) === 8) {
                $decoded .= chr(bindec($chunk));
            }
        }

        return $decoded;
    }

    public function prefix(): string
    {
        return 'b';
    }
}
