<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\ChatObject;
use PHPUnit\Framework\TestCase;
use Tests\Supports\PrimitiveAssetTest;

class ChatObjectTest extends TestCase
{
    use PrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        return [
            ['allowingIncoming', 'string', 'assertIsString']
        ];
    }

    protected function resource(array $data): ChatObject
    {
        return new ChatObject($data);
    }
}
