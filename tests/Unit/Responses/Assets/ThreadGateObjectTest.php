<?php

namespace Test\Unit\Responses\Assets;

use Atproto\Responses\Objects\LabelObject;
use Atproto\Responses\Objects\ListsObject;
use Atproto\Responses\Objects\ThreadGateObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;

class ThreadGateObjectTest extends TestCase
{
    use NonPrimitiveAssetTest;

    /**
     * @param  array  $data
     * @return LabelObject
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): ThreadGateObject
    {
        return new ThreadGateObject($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        return [
            ['lists', ListsObject::class, []],
        ];
    }
}
