<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\ChatObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class AssociatedObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['lists', $this->faker->numberBetween(1, 10), 'assertIsInt'],
            ['feedgens', $this->faker->numberBetween(1, 10), 'assertIsInt'],
            ['starterPacks', $this->faker->numberBetween(1, 10), 'assertIsInt'],
            ['labeler', $this->faker->boolean, 'assertIsBool'],
        ];
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['chat', ChatObject::class, ['allowIncoming' => $this->faker->shuffleString]],
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource($data): AssociatedObject
    {
        return new AssociatedObject($data);
    }
}
