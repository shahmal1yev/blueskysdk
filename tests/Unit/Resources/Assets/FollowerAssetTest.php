<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\FollowerAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\UserAssetTest;

class FollowerAssetTest extends TestCase
{
    use UserAssetTest;

    /**
     * @param  array  $data
     * @return FollowerAsset
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): FollowerAsset
    {
        return new FollowerAsset($data);
    }
}
