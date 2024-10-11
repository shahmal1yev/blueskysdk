<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use PHPUnit\Framework\TestCase;

class ImageCollectionTest extends TestCase
{
    use EmbedCollectionTest;

    private string $target = ImageCollection::class;
    private string $dependency = ImageInterface::class;
    private int $maxLength = 4;
    private int $maxSizeOfItem = 1000000;
}
