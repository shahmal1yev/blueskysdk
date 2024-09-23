<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\RichText\Link;
use Faker\Factory;
use Faker\Generator;
use ReflectionException;
use Tests\Supports\Reflection;

class LinkTest extends FeatureAbstractTest
{
    use Reflection;

    private Generator $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function testConstructorThrowsExceptionWhenPassedInvalidDataType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid URI: 123");

        new Link(123);
    }

    public function testLinkThrowsExceptionWhenPassedInvalidURL()
    {
        $invalidURL = $this->faker->word;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid URI: $invalidURL");

        new Link($invalidURL);
    }

    /**
     * @throws InvalidArgumentException|ReflectionException
     */
    public function testLinkConstructorWorksCorrectly()
    {
        $url = $this->faker->url;
        $link = new Link($url);

        $this->assertSame($url, $this->getPropertyValue('url', $link));
    }
}
