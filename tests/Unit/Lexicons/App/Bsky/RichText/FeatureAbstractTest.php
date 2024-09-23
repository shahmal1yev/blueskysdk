<?php

namespace Tests\Unit\Lexicons\App\Bsky\RichText;

use Atproto\Lexicons\App\Bsky\RichText\Link;
use Atproto\Lexicons\App\Bsky\RichText\Mention;
use Atproto\Lexicons\App\Bsky\RichText\Tag;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class FeatureAbstractTest extends TestCase
{
    use Reflection;

    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    /** @dataProvider featureProvider */
    public function testFeatureSerializationIsCorrect(string $class, string $input, array $expectedSchema): void
    {
        $instance = new $class($input);

        $this->assertSame($this->buildExpectedJson($expectedSchema), json_encode($instance));
        $this->assertSame($this->buildExpectedJson($expectedSchema), (string)$instance);
    }

    public function featureProvider(): array
    {
        $faker = Factory::create();

        $url = $faker->url;
        $did = $faker->uuid;
        $tag = $faker->word;

        return [
            [Link::class, $url, ['type' => 'link', 'uri' => $url]],
            [Mention::class, $did, ['type' => 'mention', 'did' => $did]],
            [Tag::class, $tag, ['type' => 'tag', 'tag' => $tag]],
        ];
    }

    private function buildExpectedJson(array $schema): string
    {
        return json_encode($schema);
    }
}
