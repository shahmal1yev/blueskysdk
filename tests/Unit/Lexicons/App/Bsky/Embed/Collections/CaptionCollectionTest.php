<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionInterface;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;
use PHPUnit\Framework\TestCase;

class CaptionCollectionTest extends TestCase
{
    use EmbedCollectionTest;

    private string $target = CaptionCollection::class;
    private string $dependency = CaptionInterface::class;
    private int $maxLength = 20;
}
