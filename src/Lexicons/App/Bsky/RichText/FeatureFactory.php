<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class FeatureFactory
{

    public static function link(string $reference, string $label = null): Link
    {
        return new Link($reference, $label);
    }

    public static function tag(string $reference, string $label = null): Tag
    {
        return new Tag($reference, $label);
    }

    public static function mention(string $reference, string $label): Mention
    {
        return new Mention($reference, $label);
    }
}
