<?php

namespace Tests\Unit\Resources\Assets\Resources\Assets;

use Atproto\Resources\Assets\BlockingByListAsset;
use Atproto\Resources\Assets\FollowersAsset;
use Atproto\Resources\Assets\KnownFollowersAsset;
use Atproto\Resources\Assets\LabelsAsset;
use Atproto\Resources\Assets\MutedByListAsset;
use Atproto\Resources\Assets\ViewerAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class ViewerAssetTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): ViewerAsset
    {
        return new ViewerAsset($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        return [
            ['mutedByList', MutedByListAsset::class, []],
            ['blockingByList', BlockingByListAsset::class, []],
            ['knownFollowers', KnownFollowersAsset::class, []],
            ['labels', LabelsAsset::class, []],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        return [
            ['muted', true, 'assertIsBool'],
            ['blocked', '', 'assertIsString'],
        ];
    }
}
