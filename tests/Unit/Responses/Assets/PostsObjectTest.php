<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\PostObject;
use Atproto\Responses\Objects\PostsObject;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class PostsObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): PostsObject
    {
        return new PostsObject($data);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAssetWithNonPrimitiveTypes(): void
    {
        $data = $this->generateData();

        $posts = $this->resource($data);

        foreach ($posts as $post) {
            $this->assertLabelMatchesComplexData($post);
        }
    }

    protected function assertLabelMatchesComplexData(PostObject $post): void
    {
        list(, , $schema) = self::getData();

        foreach ($schema as $key => $datum) {
            $expected = $datum['casted'];
            $this->assertInstanceOf($expected, $post->$key());
            $this->assertInstanceOf($expected, $post->get($key));
        }
    }

    protected function generateData(): array
    {
        $range = range(1, $this->faker->numberBetween(1, 20));

        return array_map(function () {
            list(, , $schema) = static::getData();

            return array_combine(
                array_keys($schema),
                array_column($schema, 'value')
            );
        }, $range);
    }

    protected static function getData(): array
    {
        $properties = static::assetTestGetData();

        list($faker) = $properties;

        $schema = [
            'indexedAt' => [
                'caster' => DatetimeObject::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
        ];

        return array_merge($properties, [$schema]);
    }
}
