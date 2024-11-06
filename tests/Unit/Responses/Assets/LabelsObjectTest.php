<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\LabelObject;
use Atproto\Responses\Objects\LabelsObject;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class LabelsObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): LabelsObject
    {
        return new LabelsObject($data);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testAssetWithNonPrimitiveTypes(): void
    {
        $data = $this->generateData();

        $labels = $this->resource($data);

        foreach ($labels as $label) {
            $this->assertLabelMatchesComplexData($label);
        }
    }

    protected function assertLabelMatchesComplexData(LabelObject $label): void
    {
        list(, , $schema) = self::getData();

        foreach ($schema as $key => $datum) {
            $expected = $datum['casted'];
            $this->assertInstanceOf($expected, $label->$key());
            $this->assertInstanceOf($expected, $label->get($key));
        }
    }

    protected function generateData(): array
    {
        $range = range(1, $this->faker->numberBetween(1, 20));

        return array_map(fn () => [
            'cts' => $this->faker->dateTime->format(DATE_ATOM),
            'exp' => $this->faker->dateTime->format(DATE_ATOM),
        ], $range);
    }

    protected static function getData(): array
    {
        $properties = static::assetTestGetData();

        list($faker) = $properties;

        $schema = [
            'cts' => [
                'caster' => DatetimeObject::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
            'exp' => [
                'caster' => DatetimeObject::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
        ];

        return array_merge($properties, [$schema]);
    }
}
