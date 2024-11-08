<?php

namespace Tests\Unit\Responses\Assets\Resources\Assets;

use Atproto\Responses\Objects\BlockingByListObject;
use Atproto\Responses\Objects\KnownFollowersObject;
use Atproto\Responses\Objects\LabelsObject;
use Atproto\Responses\Objects\MutedByListObject;
use Atproto\Responses\Objects\ViewerObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class ViewerObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): ViewerObject
    {
        return new ViewerObject($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        return [
            ['mutedByList', MutedByListObject::class, []],
            ['blockingByList', BlockingByListObject::class, []],
            ['knownFollowers', KnownFollowersObject::class, []],
            ['labels', LabelsObject::class, []],
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
