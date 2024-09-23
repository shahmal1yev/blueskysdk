<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\Mention;
use ReflectionException;

class MentionTest extends FeatureAbstractTest
{
    /**
     * @throws ReflectionException
     */
    public function testConstructorWorksCorrectly(): void
    {
        $expected = $this->faker->word;
        $mention = new Mention($expected);

        $this->assertSame($expected, $this->getPropertyValue('did', $mention));
    }
}
