<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\DatetimeAsset;
use Atproto\Resources\Assets\LabelAsset;
use Atproto\Resources\Assets\LabelsAsset;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class LabelsAssetTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): LabelsAsset
    {
        return new LabelsAsset($data);
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

    protected function assertLabelMatchesComplexData(LabelAsset $label): void
    {
        list(,,$schema) = self::getData();

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
                'caster' => DatetimeAsset::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
            'exp' => [
                'caster' => DatetimeAsset::class,
                'casted' => Carbon::class,
                'value'  => $faker->dateTime->format(DATE_ATOM)
            ],
        ];

        return array_merge($properties, [$schema]);
    }
}
