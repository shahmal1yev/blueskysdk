<?php

namespace Tests\Supports;

use Atproto\Resources\Assets\LabelsAsset;
use Atproto\Resources\Assets\ViewerAsset;
use Carbon\Carbon;

trait ByListAssetTest
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        return [
            ['uri', 'string', 'assertIsString'],
            ['cid', 'string', 'assertIsString'],
            ['name', 'string', 'assertIsString'],
            ['avatar', 'string', 'assertIsString'],
            ['listItemCount', 1, 'assertIsInt'],
        ];
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['labels', LabelsAsset::class, []],
            ['viewer', ViewerAsset::class, []],
            ['indexedAt', Carbon::class, $this->faker->dateTime->format(DATE_ATOM)],
        ];
    }
}
