<?php

namespace Atproto\Support;

class Arr
{
    /**
     * Get a value from an array using "dot" notation
     *
     * @param array $array The array to search
     * @param string $key The key to retrieve, with dot notation for nested keys
     * @param mixed $default (optional) The default value to return if the key is not found
     * @return mixed The value if found, otherwise the default value or null
     */
    public static function get(array $array, string $key, $default = null)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        $segments = explode('.', $key);

        foreach($segments as $segment) {
            if (! self::exists($array, $segment)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Check if a key exists in an array
     *
     * @param mixed $array The array to check
     * @param string $key The key to check for existence
     * @return bool True if the key exists, otherwise false
     */
    public static function exists($array, string $key): bool
    {
        if (! is_array($array)) {
            return false;
        }

        if (! array_key_exists($key, $array)) {
            return false;
        }

        return true;
    }

    /**
     * Remove a value from an array using "dot" notation.
     *
     * This method removes a value from the array using "dot" notation,
     * which allows you to specify the nested array key using dot syntax.
     * For example, if you have an array like ['foo' => ['bar' => 'baz']],
     * you can remove the 'bar' key by calling forget($array, 'foo.bar').
     *
     * @param array $array The array to modify
     * @param string $key The key to remove (supports "dot" notation)
     * @return void
     */
    public static function forget(array &$array, string $key): void
    {
        $parts = explode(".", $key);

        while(count($parts) > 1) {
            $part = array_shift($parts);

            if (isset($array[$part])) {
                $array = &$array[$part];
            }
        }

        unset($array[array_shift($parts)]);
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param array $array
     * @param string $keys
     * @return bool
     */
    public static function has(array $array, string $keys): bool
    {
        $keys = (array) $keys;

        if (! $array || $keys === []) {
            return false;
        }

        foreach ($keys as $key) {
            $subKeyArray = $array;

            if (static::exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (static::exists($subKeyArray, $segment)) {
                    $subKeyArray = $subKeyArray[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Retrieve a value from an array using "dot" notation and remove it.
     *
     * This method retrieves a value from the array using "dot" notation,
     * which allows you to specify the nested array key using dot syntax.
     * For example, if you have an array like ['foo' => ['bar' => 'baz']],
     * you can pull the value of 'bar' by calling pull($array, 'foo.bar').
     * The specified key is then removed from the array.
     *
     * @param array $array The array to search
     * @param string $key The key to retrieve (supports "dot" notation)
     * @param mixed $default The default value to return if the key is not found
     * @return mixed The retrieved value or the default value
     */
    public static function pull(array &$array, string $key, $default = null)
    {
        $value = self::get($array, $key, $default);

        self::forget($array, $key);

        return $value;
    }
}
