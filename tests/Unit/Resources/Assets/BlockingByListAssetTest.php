<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\BlockingByListAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\ByListAssetTest;

class BlockingByListAssetTest extends TestCase
{
    use ByListAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): BlockingByListAsset
    {
        return new BlockingByListAsset($data);
    }
}
