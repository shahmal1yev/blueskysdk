<?php

if (! function_exists('class_uses_recursive')) {
    /**
     * Returns all traits used by a class, its parent classes and trait of their traits.
     *
     * @param  object|string  $class
     * @return array
     */
    function class_uses_recursive($class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class) ?: []) + [$class => $class] as $class) {
            $results += trait_uses_recursive($class);
        }

        return array_unique($results);
    }
}

if (! function_exists('trait_uses_recursive')) {
    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param  object|string  $trait
     * @return array
     */
    function trait_uses_recursive($trait): array
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $trait) {
            $traits += trait_uses_recursive($trait);
        }

        return $traits;
    }
}

if (! function_exists('encode_varint')) {
    function encode_varint(int $int): string
    {
        $encoded = '';

        while ($int >= 0x80) {
            $encoded .= chr(($int & 0x7F) | 0x80);
            $int >>= 7;
        }

        $encoded .= chr($int);

        return $encoded;
    }
}

if (! function_exists('decode_varint')) {
    function decode_varint(string $data): int
    {
        $number = 0;
        $shift = 0;

        foreach (str_split($data) as $char) {
            $byte = ord($char);
            $number |= ($byte & 0x7F) << $shift;

            if (($byte & 0x80) === 0) {
                break;
            }

            $shift += 7;
        }

        return $number;
    }
}
