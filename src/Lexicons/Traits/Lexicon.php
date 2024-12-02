<?php

namespace Atproto\Lexicons\Traits;

trait Lexicon
{
    use Serializable;

    public function __construct(...$arguments)
    {
    }

    public function nsid(): string
    {
        $segments = explode(
            '\\',
            str_replace('Atproto\\Lexicons\\', '', static::class)
        );

        return implode('.', array_map('lcfirst', $segments));
    }
}
