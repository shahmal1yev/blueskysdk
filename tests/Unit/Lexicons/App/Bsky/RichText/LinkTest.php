<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\Link;

class LinkTest extends FeatureAbstractTest
{
    use FeatureTests;

    private string $namespace = Link::class;
    private string $type = 'link';
    private string $key = 'uri';
    private string $prefix = '';
}
