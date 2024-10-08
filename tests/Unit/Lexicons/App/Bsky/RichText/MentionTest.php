<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\Mention;

class MentionTest extends FeatureAbstractTest
{
    use FeatureTests;

    private string $namespace = Mention::class;
    private string $type = 'mention';
    private string $key = 'did';
    private string $prefix = '@';
}
