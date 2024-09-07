<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use Atproto\Resources\Assets\ChatAsset;
use PHPUnit\Framework\TestCase;
use Tests\Supports\PrimitiveAssetTest;

class ChatAssetTest extends TestCase
{
    use PrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        return [
            ['allowingIncoming', 'string', 'assertIsString']
        ];
    }

    protected function resource(array $data): ChatAsset
    {
        return new ChatAsset($data);
    }
}
