<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\RichText\Tag;
use ReflectionException;

class TagTest extends FeatureAbstractTest
{
    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function testConstructorWorksCorrectly(): void
    {
        $expected = $this->faker->word;
        $tag = new Tag($expected);

        $this->assertSame($expected, $this->getPropertyValue('tag', $tag));
    }
}
