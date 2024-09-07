<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\MutedByListAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\ByListAssetTest;

class MutedByListAssetTest extends TestCase
{
    use ByListAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): MutedByListAsset
    {
        return new MutedByListAsset($data);
    }
}
