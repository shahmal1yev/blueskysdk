<?php

if (! function_exists('throw_if')) {
    /**
     * @throws Throwable
     */
    function throw_if(bool $condition, Throwable $throwable): void
    {
        if ($condition) {
            throw $throwable;
        }
    }
}