<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\LabelObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\PostObject;
use Atproto\Responses\Objects\ProfileObject;
use Atproto\Responses\Objects\ThreadGateObject;
use Atproto\Responses\Objects\ViewerObject;
use Carbon\Carbon;
use Faker\Generator;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class PostObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @param  array  $data
     * @return LabelObject
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): PostObject
    {
        return new PostObject($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($faker) = self::getData();

        return [
            ['indexedAt', Carbon::class, $faker->dateTime->format(DATE_ATOM)],
            ['author', ProfileObject::class, []],
            ['viewer', ViewerObject::class, []],
            ['labels', LabelsObject::class, []],
            ['threadgate', ThreadGateObject::class, []],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        /** @var Generator $faker */
        list($faker) = self::getData();

        return [
            ['uri', $faker->url, 'assertIsString'],
            ['cid', $faker->uuid, 'assertIsString'],
            ['replyCount', $faker->randomDigit(), 'assertIsInt'],
            ['repostCount', $faker->randomDigit(), 'assertIsInt'],
            ['likeCount', $faker->randomDigit(), 'assertIsInt'],
            ['quoteCount', $faker->randomDigit(), 'assertIsInt'],
        ];
    }
}
