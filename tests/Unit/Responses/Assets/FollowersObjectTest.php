<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\FollowerObject;
use Atproto\Responses\Objects\FollowersObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\ViewerObject;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\Interfaces\GenericCollectionInterface;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class FollowersObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testConstructorReturnsCorrectInstance(): void
    {
        $instance = $this->resource([]);

        $this->assertAssetInstance($instance, FollowersObject::class);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAssetIsIterable(): void
    {
        $asset = $this->resource([]);

        $this->assertIsIterable($asset);
    }

    /**
     * @throws InvalidArgumentException
     * @throws BadAssetCallException
     */
    public function testAssetWithPrimitiveTypes(): void
    {
        $data = $this->generateFollowerData();

        $followers = $this->resource($data);

        foreach ($data as $index => $datum) {
            /** @var FollowerObject $follower */
            $follower = $followers->get($index);
            $this->assertFollowerMatchesData($follower, $datum);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAssetWithNonPrimitiveTypes(): void
    {
        $data = $this->generateComplexFollowerData();

        $followers = $this->resource($data);

        foreach ($followers as $follower) {
            $this->assertFollowerMatchesComplexData($follower);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): FollowersObject
    {
        return new FollowersObject($data);
    }

    protected function generateFollowerData(): array
    {
        $range = range(1, $this->faker->numberBetween(1, 20));

        return array_map(fn () => [
            'did'         => $this->faker->uuid,
            'handle'      => $this->faker->userName,
            'displayName' => $this->faker->name
        ], $range);
    }

    protected function generateComplexFollowerData(): array
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

    protected function assertAssetInstance($instance, string $class): void
    {
        $this->assertInstanceOf(GenericCollectionInterface::class, $instance);
        $this->assertInstanceOf(ObjectContract::class, $instance);
        $this->assertInstanceOf($class, $instance);
    }

    /**
     * @throws BadAssetCallException
     */
    protected function assertFollowerMatchesData(FollowerObject $follower, array $data): void
    {
        foreach ($data as $key => $value) {
            $this->assertSame($follower->$key(), $value);
            $this->assertSame($follower->get($key), $value);
        }
    }

    protected function assertFollowerMatchesComplexData(FollowerObject $follower): void
    {
        list(, , $schema) = self::getData();

        foreach ($schema as $key => $datum) {
            $expected = $datum['casted'];
            $this->assertInstanceOf($expected, $follower->$key());
            $this->assertInstanceOf($expected, $follower->get($key));
        }
    }

    protected static function getData(): array
    {
        $properties = static::assetTestGetData();

        list($faker) = $properties;

        $schema = [
            'associated' => [
                'caster' => AssociatedObject::class,
                'casted' => AssociatedObject::class,
                'value'  => []
            ],
            'viewer' => [
                'caster' => ViewerObject::class,
                'casted' => ViewerObject::class,
                'value'  => []
            ],
            'createdAt' => [
                'caster' => DatetimeObject::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
            "labels" => [
                'caster' => LabelsObject::class,
                'casted' => LabelsObject::class,
                'value'  => []
            ]
        ];

        return array_merge($properties, [$schema]);
    }
}
