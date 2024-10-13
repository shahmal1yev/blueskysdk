<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\Tag;

class TagTest extends FeatureAbstractTest
{
    use FeatureTests;

    private string $namespace = Tag::class;
    private string $type = 'tag';
    private string $key = 'tag';
    private string $prefix = '#';
}
