<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\CreatorAsset;
use Atproto\Resources\Assets\UserAsset;
use PHPUnit\Framework\TestCase;
use Tests\Supports\UserAssetTest;

class CreatorAssetTest extends TestCase
{
    use UserAssetTest;

    protected function resource(array $data): CreatorAsset
    {
        return new CreatorAsset($data);
    }
}
