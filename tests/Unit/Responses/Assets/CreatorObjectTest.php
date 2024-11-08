<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\CreatorObject;
use PHPUnit\Framework\TestCase;
use Tests\Supports\UserAssetTest;

class CreatorObjectTest extends TestCase
{
    use UserAssetTest;

    protected function resource(array $data): CreatorObject
    {
        return new CreatorObject($data);
    }
}
